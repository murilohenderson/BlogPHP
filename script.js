document.addEventListener('DOMContentLoaded', function() {
    
    const botaoCriar = document.getElementById('criar-postagem');
    const secaoFormulario = document.getElementById('formulario'); 

    if (botaoCriar && secaoFormulario) {
        
        // Verifica o estado inicial do botão (definido pelo PHP)
        const estaEditando = (botaoCriar.value === 'Cancelar Edição');

        botaoCriar.addEventListener('click', function() {
            
            if (estaEditando) {
                // Se o botão for "Cancelar Edição", clicar nele
                // redireciona para a home (limpando o modo de edição)
                window.location.href = 'index.php';
            } else {
                // Se for "Criar postagem", funciona como toggle normal
                secaoFormulario.classList.toggle('formulario-visible');
                
                if (secaoFormulario.classList.contains('formulario-visible')) {
                    botaoCriar.value = 'Cancelar'; 
                } else {
                    botaoCriar.value = 'Criar postagem'; 
                }
            }
        });
        
    } else {
        console.error("Não foi possível encontrar o botão 'criar-postagem' ou o formulário 'formulario'.");
    }
});