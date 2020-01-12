<?php

/**
 * Cookie functions
 */
namespace Bag2\Cookie
{
    use Bag2\Cookie\Emitter\Php73Function;
    use Bag2\Cookie\Emitter\PhpLegacyFunction;
    use const PHP_VERSION_ID;

    /**
     * @param array{expires?:int,path?:string,domain?:string,secure?:bool,httponly?:bool,samesite?:string} $default_options
     */
    function bag(array $default_options = []): Bag
    {
        return new Bag($default_options);
    }

    function create_emitter(): Emitter
    {
        if (PHP_VERSION_ID < 70300) {
            return new PhpLegacyFunction();
        }

        return new Php73Function();
    }

    function emit(Bag $cookie_bag): bool
    {
        $emitter = create_emitter();

        $success = true;

        foreach ($cookie_bag as $cookie) {
            [$name, $value, $options] = [$cookie->name, $cookie->value, $cookie->options];
            $success = $success && $emitter($name, $value, $options);
        }

        return $success;
    }
}
