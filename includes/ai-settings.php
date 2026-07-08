<?php
declare(strict_types=1);

function bk_ai_defaults(): array
{
    return [
        'ai_enabled'        => false,
        'ai_provider'       => 'grok',
        'ai_api_key'        => '',
        'ai_api_base'       => '',
        'ai_model'          => 'grok-3-mini',
        'ai_prompt_seo'     => '',
        'ai_model_seo'      => '',
    ];
}

function bk_ai_providers(): array
{
    return [
        'grok' => ['label' => 'Grok xAI', 'models' => ['grok-3-mini', 'grok-3']],
        'gpt'  => ['label' => 'OpenAI GPT', 'models' => ['gpt-4o-mini', 'gpt-4o']],
    ];
}

function bk_ai_merge(array $settings): array
{
    return array_merge(bk_ai_defaults(), $settings);
}

function bk_ai_apply_post(array $post, array $settings): array
{
    $settings = bk_ai_merge($settings);
    $providers = bk_ai_providers();
    $provider = trim((string) ($post['ai_provider'] ?? 'grok'));
    if (!isset($providers[$provider])) {
        $provider = 'grok';
    }
    $settings['ai_enabled'] = !empty($post['ai_enabled']);
    $settings['ai_provider'] = $provider;
    $settings['ai_api_base'] = rtrim(trim((string) ($post['ai_api_base'] ?? '')), '/');
    $model = trim((string) ($post['ai_model'] ?? ''));
    if ($model === '' && !empty($post['ai_model_select'])) {
        $model = trim((string) $post['ai_model_select']);
    }
    $settings['ai_model'] = $model !== '' ? $model : ($providers[$provider]['models'][0] ?? 'grok-3-mini');
    $key = trim((string) ($post['ai_api_key'] ?? ''));
    if ($key !== '') {
        $settings['ai_api_key'] = $key;
        $settings['ai_enabled'] = true;
    }
    $settings['ai_prompt_seo'] = trim((string) ($post['ai_prompt_seo'] ?? ''));
    $seoModel = trim((string) ($post['ai_model_seo'] ?? ''));
    if ($seoModel === '' && !empty($post['ai_model_seo_select'])) {
        $seoModel = trim((string) $post['ai_model_seo_select']);
    }
    $settings['ai_model_seo'] = $seoModel;
    return $settings;
}