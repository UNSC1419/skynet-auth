<?php

namespace Seat\UNSC1419\SkynetAuth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SkynetEsiUpdateController
{
    public function usercharacters(Request $request)
    {
        // 验证必填参数
        if (!$request->has(['user_id', 'signature'])) {
            return response()->json(['error' => '缺少签名参数'], 400);
        }

        // 获取请求参数
        $clientSignature = $request->input('signature');
        $requestData = $request->except('signature');

        // 生成服务端签名
        $serverSignature = $this->generateSignature($requestData);

        // 安全比较签名
        if (!hash_equals($serverSignature, $clientSignature)) {
            return response()->json(['error' => '签名无效'], 403);
        }

        // 执行命令
        try {
            $exitCode = Artisan::call('esi:update:UserCharacters', [
                'user_id' => $request->input('user_id')
            ]);
            
            if ($exitCode !== 0) {
                throw new \RuntimeException('命令返回非零状态码: '.$exitCode);
            }
            
            return response()->json(['message' => '更新任务已启动']);
            
        } catch (\Exception $e) {
            return response()->json(['error' => '内部服务器错误'], 500);
        }

    }

    private function generateSignature(array $data): string
    {
        ksort($data); // 保持参数顺序一致
        return hash_hmac(
            'sha256',
            json_encode($data),
            config('services.eveonline.client_secret') // 使用配置中的密钥
        );
    }
}