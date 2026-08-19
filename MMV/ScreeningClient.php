<?php
class ScreeningClient {
    private string $baseUrl;
    public function __construct(string $baseUrl = 'http://127.0.0.1:5000') { $this->baseUrl = rtrim($baseUrl, '/'); }
    public function screenBatch(array $pairs): array {
        $ch = curl_init($this->baseUrl . '/screen');
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER=>true, CURLOPT_POST=>true, CURLOPT_POSTFIELDS=>json_encode(['pairs'=>$pairs]), CURLOPT_HTTPHEADER=>['Content-Type: application/json'], CURLOPT_TIMEOUT=>5]);
        $raw=curl_exec($ch); $error=curl_errno($ch); curl_close($ch);
        $data=$raw ? json_decode($raw,true) : null;
        return is_array($data) ? $data : ['status'=>'error','message'=>$error ? 'Screening service is unreachable.' : 'Invalid screening response.'];
    }
}
?>
