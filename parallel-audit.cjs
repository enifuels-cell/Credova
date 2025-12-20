const fs = require('fs').promises;
const path = require('path');
const { Worker, isMainThread, parentPort, workerData } = require('worker_threads');
const os = require('os');

// Configuration
const WORKER_COUNT = os.cpus().length; // Use all available CPU cores
const AUDIT_RULES = {
    security: [
        { pattern: /password\s*=\s*['"]\w+['"]/, severity: 'high', message: 'Hardcoded password detected' },
        { pattern: /api_?key\s*=\s*['"]\w+['"]/, severity: 'high', message: 'Hardcoded API key detected' },
        { pattern: /secret\s*=\s*['"]\w+['"]/, severity: 'high', message: 'Hardcoded secret detected' },
        { pattern: /\$_GET\[|\$_POST\[|\$_REQUEST\[/, severity: 'medium', message: 'Direct superglobal access (use Request object)' },
        { pattern: /eval\s*\(/, severity: 'high', message: 'eval() usage detected - potential code injection' },
        { pattern: /exec\s*\(|shell_exec\s*\(|system\s*\(/, severity: 'high', message: 'Command execution detected' },
        { pattern: /\{!!\s*\$/, severity: 'medium', message: 'Unescaped Blade output - potential XSS' },
        { pattern: /DB::raw\s*\([^?]/, severity: 'medium', message: 'DB::raw without parameterization' },
        { pattern: /whereRaw\s*\([^?]/, severity: 'medium', message: 'whereRaw without parameterization' },
        { pattern: /md5\s*\(|sha1\s*\(/, severity: 'medium', message: 'Weak hashing algorithm detected' }
    ],
    performance: [
        { pattern: /N\+1|n\+1/i, severity: 'medium', message: 'Potential N+1 query issue mentioned' },
        { pattern: /\.all\(\)\.where/, severity: 'low', message: 'Inefficient query pattern - filter before loading' },
        { pattern: /foreach.*->save\(\)/, severity: 'medium', message: 'Consider bulk operations instead of loops' },
        { pattern: /sleep\s*\(|usleep\s*\(/, severity: 'low', message: 'Sleep function usage - may affect performance' }
    ],
    maintainability: [
        { pattern: /function\s+\w+\s*\([^)]*\)\s*{[^}]{500,}/, severity: 'low', message: 'Large function detected (>500 chars)' },
        { pattern: /class\s+\w+[^{]*{[^}]{2000,}/, severity: 'medium', message: 'Large class detected (>2000 chars)' },
        { pattern: /\/\/\s*TODO|\/\/\s*FIXME|\/\*\s*TODO|\/\*\s*FIXME/i, severity: 'low', message: 'TODO/FIXME comment found' },
        { pattern: /\$\$|\$\$\$/, severity: 'low', message: 'Unusual variable naming detected' }
    ]
};

class ParallelAuditScanner {
    constructor() {
        this.results = [];
        this.totalFiles = 0;
        this.processedFiles = 0;
        this.startTime = Date.now();
    }

    async scanDirectory(directory = './app') {
        console.log(`🔍 Starting parallel security audit scan...`);
        console.log(`📁 Scanning directory: ${directory}`);
        console.log(`🖥️  Using ${WORKER_COUNT} CPU cores`);

        try {
            // Get all PHP files
            const files = await this.getAllPhpFiles(directory);
            this.totalFiles = files.length;
            
            console.log(`📄 Found ${this.totalFiles} PHP files to scan`);

            if (files.length === 0) {
                console.log('❌ No PHP files found to scan');
                return;
            }

            // Split files into chunks for parallel processing
            const chunks = this.chunkArray(files, Math.ceil(files.length / WORKER_COUNT));
            
            // Create workers and process chunks in parallel
            const workerPromises = chunks.map((chunk, index) => 
                this.processChunk(chunk, index)
            );

            const workerResults = await Promise.all(workerPromises);
            
            // Combine results from all workers
            this.results = workerResults.flat();
            
            this.generateReport();
            
        } catch (error) {
            console.error('❌ Audit scan failed:', error.message);
            process.exit(1);
        }
    }

    async getAllPhpFiles(directory) {
        const files = [];
        
        async function scanDir(dir) {
            const entries = await fs.readdir(dir, { withFileTypes: true });
            
            for (const entry of entries) {
                const fullPath = path.join(dir, entry.name);
                
                if (entry.isDirectory()) {
                    // Skip certain directories
                    if (!['vendor', 'node_modules', 'storage', 'bootstrap/cache'].includes(entry.name)) {
                        await scanDir(fullPath);
                    }
                } else if (entry.name.endsWith('.php')) {
                    files.push(fullPath);
                }
            }
        }
        
        await scanDir(directory);
        return files;
    }

    chunkArray(array, chunkSize) {
        const chunks = [];
        for (let i = 0; i < array.length; i += chunkSize) {
            chunks.push(array.slice(i, i + chunkSize));
        }
        return chunks;
    }

    async processChunk(files, workerIndex) {
        return new Promise((resolve, reject) => {
            const worker = new Worker(__filename, {
                workerData: { files, workerIndex, auditRules: AUDIT_RULES }
            });

            worker.on('message', (result) => {
                this.processedFiles += result.processedCount;
                this.updateProgress();
                resolve(result.issues);
            });

            worker.on('error', reject);
            worker.on('exit', (code) => {
                if (code !== 0) {
                    reject(new Error(`Worker ${workerIndex} stopped with exit code ${code}`));
                }
            });
        });
    }

    updateProgress() {
        const percentage = Math.round((this.processedFiles / this.totalFiles) * 100);
        const elapsed = ((Date.now() - this.startTime) / 1000).toFixed(1);
        process.stdout.write(`\r⏳ Progress: ${percentage}% (${this.processedFiles}/${this.totalFiles}) - ${elapsed}s elapsed`);
    }

    generateReport() {
        console.log('\n\n🔍 PARALLEL SECURITY AUDIT REPORT');
        console.log('='.repeat(50));
        
        const duration = ((Date.now() - this.startTime) / 1000).toFixed(2);
        const filesPerSecond = (this.totalFiles / duration).toFixed(1);
        
        console.log(`⏱️  Scan completed in ${duration} seconds`);
        console.log(`🚀 Performance: ${filesPerSecond} files/second`);
        console.log(`📁 Files scanned: ${this.totalFiles}`);
        console.log(`🔍 Issues found: ${this.results.length}\n`);

        if (this.results.length === 0) {
            console.log('✅ No security issues detected!');
            return;
        }

        // Group results by severity
        const grouped = this.groupBySeverity(this.results);
        
        ['high', 'medium', 'low'].forEach(severity => {
            if (grouped[severity] && grouped[severity].length > 0) {
                console.log(`\n${this.getSeverityIcon(severity)} ${severity.toUpperCase()} SEVERITY (${grouped[severity].length} issues):`);
                console.log('-'.repeat(40));
                
                grouped[severity].forEach((issue, index) => {
                    console.log(`${index + 1}. ${issue.file}:${issue.line}`);
                    console.log(`   ${issue.message}`);
                    console.log(`   Code: ${issue.code.trim()}\n`);
                });
            }
        });

        // Generate summary statistics
        this.generateStatistics();
    }

    groupBySeverity(results) {
        return results.reduce((acc, issue) => {
            if (!acc[issue.severity]) acc[issue.severity] = [];
            acc[issue.severity].push(issue);
            return acc;
        }, {});
    }

    getSeverityIcon(severity) {
        const icons = { high: '🚨', medium: '⚠️', low: 'ℹ️' };
        return icons[severity] || '❓';
    }

    generateStatistics() {
        console.log('\n📊 SCAN STATISTICS');
        console.log('-'.repeat(30));
        
        const fileStats = this.results.reduce((acc, issue) => {
            acc[issue.file] = (acc[issue.file] || 0) + 1;
            return acc;
        }, {});

        const topFiles = Object.entries(fileStats)
            .sort(([,a], [,b]) => b - a)
            .slice(0, 5);

        if (topFiles.length > 0) {
            console.log('🔥 Files with most issues:');
            topFiles.forEach(([file, count], index) => {
                console.log(`${index + 1}. ${path.basename(file)}: ${count} issues`);
            });
        }

        const categoryStats = this.results.reduce((acc, issue) => {
            acc[issue.category] = (acc[issue.category] || 0) + 1;
            return acc;
        }, {});

        console.log('\n📋 Issues by category:');
        Object.entries(categoryStats).forEach(([category, count]) => {
            console.log(`• ${category}: ${count} issues`);
        });
    }
}

// Worker thread code
if (!isMainThread) {
    const { files, workerIndex, auditRules } = workerData;
    
    (async () => {
        const issues = [];
        let processedCount = 0;
        
        for (const file of files) {
            try {
                const content = await fs.readFile(file, 'utf8');
                const lines = content.split('\n');
                
                // Check each category of rules
                Object.entries(auditRules).forEach(([category, rules]) => {
                    rules.forEach(rule => {
                        lines.forEach((line, lineIndex) => {
                            if (rule.pattern.test(line)) {
                                issues.push({
                                    file: file,
                                    line: lineIndex + 1,
                                    severity: rule.severity,
                                    message: rule.message,
                                    category: category,
                                    code: line
                                });
                            }
                        });
                    });
                });
                
                processedCount++;
                
            } catch (error) {
                console.error(`Error processing ${file}:`, error.message);
            }
        }
        
        parentPort.postMessage({ issues, processedCount });
    })();
}

// Main execution
if (isMainThread) {
    const scanner = new ParallelAuditScanner();
    
    // Get directory from command line args or use default
    const targetDirectory = process.argv[2] || './app';
    
    scanner.scanDirectory(targetDirectory).then(() => {
        console.log('\n✅ Parallel audit scan completed!');
        process.exit(0);
    }).catch(error => {
        console.error('\n❌ Audit scan failed:', error);
        process.exit(1);
    });
}

module.exports = ParallelAuditScanner;
