<?php

namespace Seat\UNSC1419\SkynetAuth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

class SkynetEsiUpdateController
{
    /**
     * 处理用户角色更新请求
     * 
     * @param Request $request HTTP 请求对象
     * @return \Illuminate\Http\JsonResponse
     */
    public function usercharacters(Request $request)
    {
        // 请求验证
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|min:1',
            'signature' => 'required|string|size:64', // SHA256签名为64字符
            'timestamp' => 'required|integer|min:1|max:2147483647' 
        ]);

        if ($validator->fails()) {

            return response()->json(['error' => '参数无效'], 400);
        }

        $timestamp = (int)$request->input('timestamp');
        $userId = (int)$request->input('user_id');
        $requestData = [
            'user_id' => $userId,
            'timestamp' => $timestamp,
        ];
        

        // 时间戳验证（5分钟有效期）
        if (abs(time() - $timestamp) > 300) {

            return response()->json(['error' => '请求已过期'], 403);
        }

        // 签名验证
        if (!$this->verifySignature($requestData, $request->input('signature'))) {

            return response()->json(['error' => '签名无效'], 403);
        }

        // 执行命令
        try {


            $exitCode = Artisan::call('esi:update:UserCharacters', [
                'user_id' => $userId,
            ]);

            if ($exitCode !== 0) {
                throw new \RuntimeException("Artisan命令返回非零状态码: {$exitCode}");
            }

            return response()->json([
                'message' => '更新任务已加入队列'
            ]);

        } catch (\Exception $e) {

            return response()->json(['error' => '内部服务器错误'], 500);
        }
    }

    /**
     * 验证请求签名
     * 
     * @param array $data 请求数据
     * @param string $clientSignature 客户端签名
     * @return bool
     */
    private function verifySignature(array $data, string $clientSignature): bool
    {
        $serverSignature = $this->generateSignature($data);
        return hash_equals($serverSignature, $clientSignature);
    }

    /**
     * 生成HMAC-SHA256签名
     * 
     * @param array $data 待签名数据
     * @return string
     */
    private function generateSignature(array $data): string
    {

        if (!is_array($data) || empty($data)) {
            throw new \InvalidArgumentException('签名数据格式无效');
        }

        $secret = config('services.eveonline.client_secret');
        if (empty($secret)) {
            throw new \RuntimeException('未配置签名密钥');
        }

        // 这里使用ksort确保参数顺序一致
        ksort($data);
        
        // 使用更安全的序列化方式
        $stringToSign = collect($data)
            ->map(fn ($value, $key) => "{$key}={$value}")
            ->implode('&');

        return hash_hmac(
            'sha256',
            $stringToSign,
            $secret
        );
    }
}
