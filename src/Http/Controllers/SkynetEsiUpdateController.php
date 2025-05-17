<?php

namespace Seat\UNSC1419\SkynetAuth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SkynetEsiUpdateController
{
    /**
     * 处理用户角色更新请求
     * 
     * 验证请求签名并执行 ESI 用户角色更新命令
     * 
     * @param Request $request HTTP 请求对象，需包含 user_id 和 signature 参数
     * @return \Illuminate\Http\JsonResponse 返回JSON格式响应：
     *     - 成功：{"message": "更新任务已启动"}
     *     - 参数缺失：400错误 {"error": "缺少签名参数"}
     *     - 签名无效：403错误 {"error": "签名无效"}
     *     - 命令执行失败：500错误 {"error": "内部服务器错误"}
     */
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

    /**
     * 使用HMAC-SHA256算法生成数据签名
     * 
     * 对输入数组按键名排序后，使用配置中的client_secret作为密钥生成签名
     * 
     * @param array $data 待签名的数据数组
     * @return string 返回生成的16进制小写签名字符串
     */
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