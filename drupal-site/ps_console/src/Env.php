<?php
namespace Tci\PsConsole;

class Env
{
    /**
     * Get the env value.
     *
     * @param string $key
     *
     * @return string
     * @throw RuntimeException
     */
    public function get($key)
    {
        $value = getenv($key);

        if (is_bool($value)) {
            throw new \RuntimeException(sprintf('key not defined in env: %s', $key));
        }

        return $value;
    }
}
