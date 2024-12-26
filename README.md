# QQ登录中转API
 > 二开自彩虹聚合登录

## SDK下载

Gitee：[https://gitee.com/ShuShuicu/qloginAPI-SDK](https://gitee.com/ShuShuicu/qloginAPI-SDK)
GitHub：[https://github.com/ShuShuicu/qloginAPI-SDK](https://github.com/ShuShuicu/qloginAPI-SDK)

## 开发文档
### 接口协议规则

|传输方式 |数据格式|字符编码|
| --- | --- | --- |
|HTTP|JSON|UTF-8|

### 登录流程
#### Step1：获取跳转登录地址  
请求URL：
~~~
https://domain.com/connect.php?act=login&appid={appid}&appkey={appkey}&type=qq&redirect_uri={回调地址}  
~~~
返回格式：
~~~
{
  "code": 0,
  "msg": "succ",
  "type": "qq",
  "url": "https://graph.qq.com/oauth2.0/XXXXXXXXXX"
}
~~~
返回参数说明：


| 参数名 | 参数类型 | 参数说明 | 参数示例 |
|--------|----------|----------|----------|
| code   | int      | 返回状态码 | 0为成功，其它值为失败 |
| msg    | string   | 返回信息 | 返回错误时的说明 |
| type   | string   | 登录方式 | qq |
| url    | string   | 登录跳转地址 | https://graph.qq.com/oauth2.0/XXXXXXXXXX |

#### Step2：跳转到登录地址
登录地址为上一步返回的url的值。

#### Step3：登录成功会自动跳转到指定的redirect_uri，并跟上Authorization Code
例如回调地址是：www.qq.com/my.php，则会跳转到：  
http://www.qq.com/my.php?type=qq&code=520DD95263C1CFEA0870FBB66E******

#### Step4：通过Authorization Code获取用户信息
请求URL： https://domain.com/connect.php?act=callback&appid={appid}&appkey={appkey}&type=qq&code={code}  
返回格式：
~~~
{
  "code": 0,
  "msg": "succ",
  "type": "qq",
  "access_token": "89DC9691E274D6B596FFCB8D43368234",
  "social_uid": "AD3F5033279C8187CBCBB29235D5F827",
  "faceimg": "https://thirdqq.qlogo.cn/g?b=oidb&k=3WrWp3peBxlW4MFxDgDJEQ&s=100&t=1919810",
  "nickname": "伊地知虹夏",
  "location": "下北泽",
  "gender": "女",
  "ip": "11.45.14"
}
~~~

### 返回参数说明：

| 参数名       | 参数类型 | 参数说明                         | 参数示例                                   |
|--------------|----------|----------------------------------|--------------------------------------------|
| code         | int      | 返回状态码                       | 0为成功，2为未完成登录，其它值为失败       |
| msg          | string   | 返回信息                         | 返回错误时的说明                           |
| type         | string   | 登录方式                         | qq                                         |
| social_uid   | string   | 第三方登录UID                    | AD3F5033279C8187CBCBB29235D5F827             |
| access_token | string   | 第三方登录token                  | 89DC9691E274D6B596FFCB8D43368234             |
| faceimg      | string   | 用户头像                         | https://thirdqq.qlogo.cn/g?......            |
| nickname     | string   | 用户昵称                         | 伊地知虹夏                               |
| gender       | string   | 用户性别                         | 女                                         |
| ip           | string   | 用户登录IP                       | 11.45.14                                  |

### 获取用户信息接口
在用户登录后的任意时间，可以请求以下接口再次查询用户的详细信息。

请求URL：
~~~
https://domain.com/connect.php?act=query&appid={appid}&appkey={appkey}&type=qq&social_uid={social_uid}
~~~
social_uid就是用户的第三方登录UID，用于识别用户的唯一字段。

返回格式：
~~~
{
  "code": 0,
  "msg": "succ",
  "type": "qq",
  "social_uid": "AD3F5033279C8187CBCBB29235D5F827",
  "access_token": "89DC9691E274D6B596FFCB8D43368234",
  "nickname": "伊地知虹夏",
  "faceimg": "https://thirdqq.qlogo.cn/g?b=oidb&k=ianyRGEnPZlMV2aQvvzg2uA&s=100&t=191810",
  "location": "下北泽市",
  "gender": "女",
  "ip": "11.45.14"
}
~~~

#### 返回参数说明：

| 参数名       | 参数类型 | 参数说明                         | 参数示例                                   |
|--------------|----------|----------------------------------|--------------------------------------------|
| code         | int      | 返回状态码                       | 0为成功，其它值为失败                      |
| msg          | string   | 返回信息                         | 返回错误时的说明                           |
| type         | string   | 登录方式                         | qq                                         |
| social_uid   | string   | 第三方登录UID                    | AD3F5033279C8187CBCBB29235D5F827             |
| access_token | string   | 第三方登录token                  | 89DC9691E274D6B596FFCB8D43368234             |
| faceimg      | string   | 用户头像                         | https://thirdqq.qlogo.cn/g?......            |
| nickname     | string   | 用户昵称                         | 伊地知虹夏                               |
| gender       | string   | 用户性别                         | 女                                         |
| ip           | string   | 用户登录IP                       | 11.45.14                                  |