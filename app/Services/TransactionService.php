<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class TransactionService
{
    /**
     * Execute a database transaction with comprehensive error handling
     */
    public static function execute(callable $callback, int $attempts = 3): mixed
    {
        $attempt = 0;
        
        while ($attempt < $attempts) {
            $attempt++;
            
            try {
                return DB::transaction(function () use ($callback) {
                    return $callback();
                }, 5); // 5 deadlock retries
                
            } catch (Throwable $e) {
                Log::error('Transaction failed', [
                    'attempt' => $attempt,
                    'max_attempts' => $attempts,
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                // Check if this is a retryable error
                if (!self::isRetryableError($e) || $attempt >= $attempts) {
                    throw $e;
                }
                
                // Exponential backoff before retry
                $delay = pow(2, $attempt - 1) * 100000; // microseconds
                usleep($delay);
            }
        }
        
        throw new \Exception('Transaction failed after maximum attempts');
    }

    /**
     * Execute transaction with savepoints for complex operations
     */
    public static function executeWithSavepoints(array $operations): array
    {
        $results = [];
        
        return DB::transaction(function () use ($operations, &$results) {
            foreach ($operations as $name => $operation) {
                try {
                    DB::statement("SAVEPOINT sp_{$name}");
                    $results[$name] = $operation();
                    
                } catch (Throwable $e) {
                    DB::statement("ROLLBACK TO SAVEPOINT sp_{$name}");
                    
                    Log::error("Operation '{$name}' failed, rolled back to savepoint", [
                        'operation' => $name,
                        'error' => $e->getMessage()
                    ]);
                    
                    throw $e;
                }
            }
            
            return $results;
        });
    }

    /**
     * Create a checkpoint for manual rollback
     */
    public static function createCheckpoint(string $name): void
    {
        DB::statement("SAVEPOINT {$name}");
        
        Log::info("Database checkpoint created", ['name' => $name]);
    }

    /**
     * Rollback to a specific checkpoint
     */
    public static function rollbackToCheckpoint(string $name): void
    {
        DB::statement("ROLLBACK TO SAVEPOINT {$name}");
        
        Log::warning("Rolled back to checkpoint", ['name' => $name]);
    }

    /**
     * Execute critical transaction with backup verification
     */
    public static function executeCritical(callable $callback, callable $verificationCallback = null): mixed
    {
        // Create a checkpoint before critical operation
        $checkpointName = 'critical_' . uniqid();
        self::createCheckpoint($checkpointName);
        
        try {
            $result = self::execute($callback, 1); // Single attempt for critical operations
            
            // Verify the result if verification callback provided
            if ($verificationCallback && !$verificationCallback($result)) {
                self::rollbackToCheckpoint($checkpointName);
                throw new \Exception('Critical transaction verification failed');
            }
            
            return $result;
            
        } catch (Throwable $e) {
            self::rollbackToCheckpoint($checkpointName);
            
            Log::critical('Critical transaction failed and rolled back', [
                'checkpoint' => $checkpointName,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Check if an error is retryable
     */
    private static function isRetryableError(Throwable $e): bool
    {
        $retryableErrors = [
            'Deadlock found when trying to get lock',
            'Lock wait timeout exceeded',
            'Connection lost',
            'Server has gone away',
            'Too many connections'
        ];
        
        foreach ($retryableErrors as $retryableError) {
            if (str_contains($e->getMessage(), $retryableError)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Monitor transaction performance
     */
    public static function executeWithMonitoring(callable $callback, string $operationName = 'transaction'): mixed
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);
        
        try {
            $result = self::execute($callback);
            
            $endTime = microtime(true);
            $endMemory = memory_get_usage(true);
            
            Log::info('Transaction performance metrics', [
                'operation' => $operationName,
                'duration_ms' => round(($endTime - $startTime) * 1000, 2),
                'memory_used_mb' => round(($endMemory - $startMemory) / 1024 / 1024, 2),
                'peak_memory_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
                'status' => 'success'
            ]);
            
            return $result;
            
        } catch (Throwable $e) {
            $endTime = microtime(true);
            
            Log::error('Transaction failed with performance metrics', [
                'operation' => $operationName,
                'duration_ms' => round(($endTime - $startTime) * 1000, 2),
                'error' => $e->getMessage(),
                'status' => 'failed'
            ]);
            
            throw $e;
        }
    }
}
