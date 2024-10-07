<?php

declare(strict_types=1);

final class Uri
{
    public function __construct(private string $uri) {}

    /**
     * @see https://stackoverflow.com/questions/6768793/get-the-full-url-in-php/8891890#8891890
     * @return Uri
     */
    public static function current(): Uri
    {
        $ssl      = ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' );
        $sp       = strtolower( $_SERVER['SERVER_PROTOCOL'] );
        $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? '_SERVER' : '' );
        $port     = $_SERVER['SERVER_PORT'];
        $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
        $host     = (isset( $_SERVER['HTTP_X_FORWARDED_HOST'] ) ) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : ($_SERVER['HTTP_HOST'] ?? null);
        $host     = $host ?? $_SERVER['SERVER_NAME'] . $port;

        return new self($protocol . '://' . $host . $_SERVER['REQUEST_URI']);
    }

    public function withQueryParam(string $name, $value): Uri
    {
        $url = parse_url($this->uri);
        parse_str($url['query'], $query);
        $query[$name] = $value;

        return new self($url['path'].'?'.http_build_query($query));
    }

    public function __toString(): string
    {
        return $this->uri;
    }
}
