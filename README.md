# Ouve a Minha Prece

Landing page de pré-registo do ebook «7 Dias para Acalmar o Coração com Deus».

Inclui logótipo, fundo Liora de olhos azuis, formulário com consentimentos separados e 15 passagens bíblicas aleatórias numa janela transparente.

## Endereço publicado

https://ouveaminhaprece.netlifly.space/

## Publicação na Hostinger

- Ramo de publicação: `main`.
- Os ficheiros desta raiz são os ficheiros de produção; não é necessária compilação.
- Requer PHP 8.0 ou superior e HTTPS. GitHub Pages não executa o formulário PHP.
- Diretório do subdomínio: `public_html/ouveaminhaprece/`.
- Os contactos ficam fora da pasta pública, em `omp-private`; nunca devem ser enviados para este repositório.
- Ver `INSTALAR.md` para instalação e verificação.

O ebook e o envio automático de email serão acrescentados numa fase posterior.

## Avisos de pré-registo

Após guardar cada pré-registo, o formulário tenta enviar um aviso para `prece@netlifly.space`, com nome, email, data e autorizações. Usa a função PHP `mail()` do alojamento, com o mesmo endereço como remetente. A entrega depende da configuração de email do Hostinger e deve ser confirmada com um teste real. Envia também uma confirmação de pré-registo ao visitante, independentemente do resultado do aviso ao administrador. A confirmação explica que o ebook está em preparação e permite responder para pedir ajuda ou cancelar o registo. Não envia ainda o ebook. Se o transporte de email falhar, o registo permanece guardado e o servidor recebe uma mensagem de diagnóstico sem dados pessoais.
