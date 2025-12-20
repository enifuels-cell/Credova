import dotenv from 'dotenv';
dotenv.config({ path: '.env.node' });
import OpenAI from "openai";
import fs from "fs-extra";
import { glob } from "glob";
import path from "path";

// ---- CONFIG ----
const openai = new OpenAI({ apiKey: process.env.OPENAI_API_KEY });
const PROJECT_DIR = process.cwd();
const OUTPUT_FILE = "audit_report.md";

// Which folders to scan
const FOLDERS = [
  "app/**/*.php",
  "routes/**/*.php",
  "resources/js/**/*.js",
  "resources/js/**/*.jsx",
  "resources/js/**/*.ts",
  "resources/js/**/*.tsx"
];

// Main audit prompt
const AUDIT_PROMPT = `
You are an expert Laravel + React auditor.
Perform a deep inspection of the provided code.

1. SECURITY REVIEW
- Authentication & authorization
- CSRF protection
- SQL injection prevention
- Session management
- XSS prevention

2. ROUTING & PATH INTEGRITY
- Middleware protection
- HTTP verb correctness
- API authentication & rate limiting

3. CODE STRUCTURE & MAINTAINABILITY
- Best practices for Laravel & React
- Unused/duplicate code
- Broken imports or paths

4. FAIL-SAFE & ROBUSTNESS
- Missing error handling
- Fallback logic for API calls & DB queries

5. PERFORMANCE
- N+1 query issues
- Caching opportunities
- React rendering optimizations

Return:
- Problem description
- File + line
- Severity (Low, Medium, High)
- Recommended fix
`;

// ---- SCAN ----
async function runAudit() {
  let report = "# 🔍 Project Audit Report\n\n";
  const files = (await Promise.all(
    FOLDERS.map(pattern => glob(pattern, { cwd: PROJECT_DIR, absolute: true }))
  )).flat();

  for (const file of files) {
    const code = await fs.readFile(file, "utf8");
    console.log(`Auditing: ${path.relative(PROJECT_DIR, file)}`);

    const response = await openai.chat.completions.create({
      model: "gpt-4o-mini",
      messages: [
        { role: "system", content: AUDIT_PROMPT },
        { role: "user", content: `File: ${path.relative(PROJECT_DIR, file)}\n\n${code}` }
      ],
      max_tokens: 1000, // Limit tokens to reduce usage
      temperature: 0
    });

    const auditResult = response.choices[0].message.content;
    report += `## ${path.relative(PROJECT_DIR, file)}\n${auditResult}\n\n`;
    await fs.writeFile(OUTPUT_FILE, report);
  }

  console.log(`✅ Audit complete. See ${OUTPUT_FILE}`);
}

runAudit().catch(console.error);
