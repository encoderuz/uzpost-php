<?phpuse Encoderuz\Uzpost\Exceptions\UzpostException;use Encoderuz\Uzpost\Http\Uzpost;it(/** * @throws UzpostException */ 'it can return Uzpost token', function () {    $uzpost = Uzpost::get_token("username", "password");    var_dump($uzpost);    expect($uzpost)->not()->toBeNull();});