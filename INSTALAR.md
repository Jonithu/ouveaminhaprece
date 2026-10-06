# Ouve a Minha Prece — Landing page Hostinger

Página de pré-registo real (nome + email), sem palavra-passe. O ebook e o flipbook ainda não estão incluídos. Não envia emails automaticamente: os avisos serão enviados depois de escolher e ligar um serviço de email. As inscrições guardam a autorização para entrega e a opção separada de novidades.

## Instalação
1. Associar netlify.space ao alojamento e ativar HTTPS.
2. Extrair este pacote para public_html/ouveaminhaprece/. Abrir https://netlify.space/ouveaminhaprece/ apenas depois da instalação.
3. Confirmar PHP 8.0 ou superior. Em config.php, a pasta omp-private é criada um nível acima de public_html, fora do acesso web. Deve ser gravável pelo PHP. Nunca mover os registos para a pasta pública.
4. Rever a identificação do responsável e o contacto na página de privacidade antes de recolher dados reais. Pode substituir-se o contacto Instagram por email próprio confirmado.
5. Submeter um registo de teste e confirmar a linha em omp-private/registos.json. Eliminar o teste. Confirmar a opção updates_consent com e sem seleção. O frontend só confirma o registo quando o servidor o guarda.
6. Consultar/exportar os registos apenas pelo gestor de ficheiros autenticado da Hostinger. Não existe painel público de administração.
7. Fazer uma rotina de limpeza dos registos com mais de um ano, mesmo quando não houver novas inscrições. Retirar consentimentos/apagar registos a pedido pelo gestor de ficheiros; guardar cópias de segurança apenas durante o período necessário.

## Fase seguinte
Produzir e validar o ebook. Ligar o envio de email com ligação de acesso. Implementar verificação de email e ligação individual antes de disponibilizar o conteúdo com registo obrigatório; o pré-registo atual não verifica a titularidade do email. Não gerar QR público até o endereço estar ativo. Não publicar uma ligação direta ao PDF se se pretende manter o acesso condicionado ao registo.

## Materiais
Medalhão aprovado e referência Liora Moderna recuperados da Drive. Fundo panorâmico criado a partir da referência da Liora. Ficheiros otimizados WebP e servidos localmente, sem dependências externas de fontes ou scripts.
