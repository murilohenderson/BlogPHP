document.addEventListener('DOMContentLoaded', function() {
    
    // --- Lógica original do formulário ---
    const botaoCriar = document.getElementById('criar-postagem');
    const secaoFormulario = document.getElementById('formulario'); 

    if (botaoCriar && secaoFormulario) {
        
        const estaEditando = (botaoCriar.value === 'Cancelar Edição');

        botaoCriar.addEventListener('click', function() {
            
            if (estaEditando) {
                // Se "Cancelar Edição", redireciona para limpar o GET da URL
                window.location.href = 'index.php';
            } else {
                // Se "Criar postagem", alterna a visibilidade
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


    // --- Adicionado: Lógica do Modal ---
    // (Esta parte é nova e controla a janela)

    // 1. Selecionar todos os elementos do modal
    const modalOverlay = document.getElementById('modal-overlay');
    const modalTitulo = document.getElementById('modal-titulo');
    const modalData = document.getElementById('modal-data');
    const modalTexto = document.getElementById('modal-texto');
    const modalFechar = document.getElementById('modal-fechar');
    
    // Seleciona TODAS as postagens que podem ser clicadas
    const postagensClicaveis = document.querySelectorAll('.postagem-clicavel');

    // 2. Função para abrir o modal
    function abrirModal(titulo, data, conteudo) {
        modalTitulo.textContent = titulo;
        modalData.textContent = "Publicado em: " + data;
        modalTexto.textContent = conteudo;
        
        // Troca a classe para exibir o modal
        modalOverlay.classList.remove('modal-overlay-hidden');
        modalOverlay.classList.add('modal-overlay-visible');
    }

    // 3. Função para fechar o modal
    function fecharModal() {
        // Troca a classe para esconder o modal
        modalOverlay.classList.remove('modal-overlay-visible');
        modalOverlay.classList.add('modal-overlay-hidden');

        // Limpa o conteúdo (boa prática)
        modalTitulo.textContent = "";
        modalData.textContent = "";
        modalTexto.textContent = "";
    }

    // 4. Adicionar listeners de clique

    // Adiciona um listener para CADA postagem
    postagensClicaveis.forEach(post => {
        post.addEventListener('click', function(e) {
            
            // Importante: Verifica se o clique foi nos botões de ação
            // Se foi, não abre o modal.
            if (e.target.closest('.btn-editar') || e.target.closest('.btn-excluir')) {
                return; 
            }

            // Pega os dados guardados nos atributos 'data-'
            const titulo = post.dataset.titulo;
            const conteudo = post.dataset.conteudo;
            const data = post.dataset.data;

            // Abre o modal com esses dados
            abrirModal(titulo, data, conteudo);
        });
    });

    // Listener para o botão de fechar (X)
    if(modalFechar) {
        modalFechar.addEventListener('click', fecharModal);
    }

    // Listener para fechar clicando no fundo (overlay)
    if(modalOverlay) {
        modalOverlay.addEventListener('click', function(e) {
            // Verifica se o clique foi EXATAMENTE no overlay (fundo)
            // e não nos filhos dele (a janela de conteúdo branca)
            if (e.target === modalOverlay) {
                fecharModal();
            }
        });
    }

});

