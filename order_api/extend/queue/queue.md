## 用户

```
    安装 think-swoole 和 think-queue
    
    调用 Queue::connection('redis')->push(TestJob::class, ['snqnd' => 12132435466], 'TestJob');

`Queue::push($job, $data = '', $queue = null)` 
和 `Queue::later($delay, $job, $data = '', $queue = null)`

```