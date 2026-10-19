<div style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 2.5rem; text-align: center; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
    <h1 style="font-size: 2.25rem; font-weight: 700; margin-bottom: 1rem; color: #fff;">
        Bem-vindo ao <?= e(env('APP_NAME', 'Delivery MVC')) ?>
    </h1>
    <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto 2rem;">
        Sua base MVC moderna, segura e pronta para ser expandida em qualquer tipo de projeto Web ou API REST.
    </p>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-top: 2rem; text-align: left;">
        <div style="background: rgba(255,255,255,0.03); border: 1px solid var(--border); border-radius: 8px; padding: 1.25rem;">
            <h3 style="font-size: 1.1rem; margin-bottom: 0.5rem; color: #818cf8;">⚡ Rápido & Leve</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Autoload PSR-4, Dotenv, e roteador com suporte a middlewares e closures.</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid var(--border); border-radius: 8px; padding: 1.25rem;">
            <h3 style="font-size: 1.1rem; margin-bottom: 0.5rem; color: #34d399;">🔒 Segurança Nativa</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Proteção CSRF, escape contra XSS e sanitização de dados no Request.</p>
        </div>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid var(--border); border-radius: 8px; padding: 1.25rem;">
            <h3 style="font-size: 1.1rem; margin-bottom: 0.5rem; color: #f472b6;">🌐 Web & API</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Layouts reutilizáveis para páginas e respostas JSON nativas para APIs.</p>
        </div>
    </div>
</div>