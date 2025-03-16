<?php
declare(strict_types=1);

namespace AisearchClient\src;

class Controller
{
    public int $site_id;
    public string $client_token;

    public function base()
    {
        return Aisearch::API . "/sites/" . $this->site_id . "/" . Aisearch::API_VERSION;
    }
}