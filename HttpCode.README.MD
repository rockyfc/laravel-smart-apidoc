# HTTP STATUS CODE 说明
- 100：如果服务器收到头信息中带有100-continue的请求，这是指客户端询问是否可以在后续的请求中发送附件。在这种情况下，服务器用100(SC_CONTINUE)允许客户端继续或用417 (Expectation Failed)告诉客户端不同意接受附件。这个状态码是 HTTP 1.1中新加入的。,
- 101：是指服务器将按照其上的头信息变为一个不同的协议。这是 HTTP 1.1中新加入的,
- 102：Processing            # RFC2518
- 103：Early Hints
- 200：一切正常
- 201：表示服务器在请求的响应中建立了新文档;应在定位头信息中给出它的URL
- 202：服务器已接受请求，但还没有处理完。
- 203：Non-Authoritative Information
- 204：内容不存在，一般，执行delete操作之后返回此状态码
- 205：服务器发送此状态码强迫浏览器清除表单域
- 206：Partial Content
- 207：Multi-Status          // RFC4918
- 208：Already Reported      // RFC5842
- 226：IM Used               // RFC3229
- 300：表示被请求的资源可以在多个地方找到，并将在返回的资源中列出来。如果服务器有首选设置，首选项将会被列于定位响应头信息中
- 301：状态是指所请求的文档在别的地方，文档新的URL会在定位响应头信息中给出。浏览器会自动连接到新的URL（重定向常用）
- 302：与301有些类似，只是定位头信息中所给的URL应被理解为临时交换地址而不是永久的（重定向常用）
- 303：这个状态码和 301、302 相似，只是如果最初的请求是 POST，那么新文档(在定位头信息中给出)药用 GET 找回。这个状态码是新加入 HTTP 1.1中
- 304：是指缓冲的版本已经被更新并且客户端应刷新文档。
- 305：表示所请求的文档要通过定位头信息中的代理服务器获得
- 307：浏览器处理307状态的规则与302相同。307状态被加入到 HTTP 1.1中是由于许多浏览器在收到302响应时即使是原始消息为POST的情况下仍然执行了错误的转向。只有在收到303响应时才假定浏览器会在POST请求时重定向。添加这个新的状态码的目的很明确：在响应为303时按照GET和POST请求转向;而在307响应时则按照GET请求转向而不是POST请求
- 308：Permanent Redirect    // RFC7238
- 400：Bad Request
- 401：Unauthorized，认证失败。
- 402：Payment Required
- 403：Forbidden，授权失败，禁止访问。
- 404：Not Found，找不到资源。
- 405：Method Not Allowed，请求方式错误。
- 406：Not Acceptable
- 407：Proxy Authentication Required
- 408：Request Timeout，请求超时。
- 409：Conflict
- 410：Gone
- 411：Length Required
- 412：Precondition Failed
- 413：Payload Too Large
- 414：URI Too Long
- 415：Unsupported Media Type
- 416：Range Not Satisfiable
- 417：Expectation Failed
- 418：I am a teapot                                               // RFC2324
- 421：Misdirected Request                                         // RFC7540
- 422：Unprocessable Entity，数据验证失败                               // RFC4918
- 423：Locked                                                      // RFC4918
- 424：Failed Dependency                                           // RFC4918
- 425：Too Early                                                   // RFC-ietf-httpbis-replay-04
- 426：Upgrade Required                                            // RFC2817
- 428：Precondition Required                                       // RFC6585
- 429：Too Many Requests                                           // RFC6585
- 431：Request Header Fields Too Large                             // RFC6585
- 451：Unavailable For Legal Reasons                               // RFC7725
- 500：Internal Server Error，服务器错误。
- 501：Not Implemented
- 502：Bad Gateway，网关错误
- 503：Service Unavailable
- 504：Gateway Timeout，网关错误
- 505：HTTP Version Not Supported
- 506：Variant Also Negotiates                                     // RFC2295
- 507：Insufficient Storage                                        // RFC4918
- 508：Loop Detected                                               // RFC5842
- 510：Not Extended                                                // RFC2774
- 511：Network Authentication Required                             // RFC6585
