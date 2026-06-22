<?php
// Definições da página de Katas para o menu.php
$pageTitle = 'Katas de Karatê - Kenshydokan';
$pageDescription = 'Assista aos vídeos demonstrativos e aprenda as técnicas de cada kata do estilo Kenshydokan.';
$ogImage = 'https://www.SEUSITE.com.br/img/kenshydokan.jpg';

include 'menu.php';

// Array com os Katas ordenados por dificuldade/base
$katas = [
    [
        'id' => 'taikyoku-sono-ichi',
        'nome' => 'Taikyoku Sono Ichi',
        'video_id' => 'sE31qWpwqAs',
        'nivel' => 'Básico',
        'nivel_classe' => 'badge-success',
        'descricao' => 'Primeiro kata básico preparatório do estilo, focado em posturas iniciais de base de caminhada (Zenkutsu Dachi) e coordenação de passos com defesas baixas (Gedan Barai).'
    ],
    [
        'id' => 'taikyoku-sono-ni',
        'nome' => 'Taikyoku Sono Ni',
        'video_id' => 'lCbj3ofAtwc',
        'nivel' => 'Básico',
        'nivel_classe' => 'badge-success',
        'descricao' => 'Segundo kata básico preparatório, focando no aprimoramento da base Zenkutsu Dachi combinada com socos frontais na altura média (Chudan Oi Tsuki).'
    ],
    [
        'id' => 'seishin-ichi',
        'nome' => 'Seishin Ichi',
        'video_id' => 'Bzek4rLW8dg',
        'nivel' => 'Intermediário',
        'nivel_classe' => 'badge-warning text-dark',
        'descricao' => 'Primeiro kata da série Seishin (Mente Limpa), focado no desenvolvimento de movimentos circulares, equilíbrio e bases de defesa pessoal.'
    ],
    [
        'id' => 'seishin-ni',
        'nome' => 'Seishin Ni',
        'video_id' => 'C1oJFvq-J58',
        'nivel' => 'Intermediário',
        'nivel_classe' => 'badge-warning text-dark',
        'descricao' => 'Segundo kata da série Seishin, introduzindo esquivas laterais coordenadas e técnicas dinâmicas de contra-ataques precisos.'
    ],
    [
        'id' => 'seishin-san',
        'nome' => 'Seishin San',
        'video_id' => 'FErZ7wPWFkQ',
        'nivel' => 'Intermediário',
        'nivel_classe' => 'badge-warning text-dark',
        'descricao' => 'Terceiro kata da série Seishin, focado na transição rápida de direções, giros coordenados e técnicas de ataque e defesa utilizando cutilada de mão (Shuto Waza).'
    ],
    [
        'id' => 'seishin-shi',
        'nome' => 'Seishin Shi',
        'video_id' => 'QB0U4oqSZrY',
        'nivel' => 'Intermediário',
        'nivel_classe' => 'badge-warning text-dark',
        'descricao' => 'Quarto kata da série Seishin, exigindo maior flexibilidade física, posturas de bases baixas e sequências rápidas de bloqueios e golpes simultâneos.'
    ],
    [
        'id' => 'seishin-go',
        'nome' => 'Seishin Go',
        'video_id' => 'kHvqjaXy9Ok',
        'nivel' => 'Avançado',
        'nivel_classe' => 'badge-danger',
        'descricao' => 'Quinto kata da série Seishin, mesclando golpes rápidos e lentos com controle avançado de respiração, técnicas de projeção e extrema estabilidade corporal.'
    ],
    [
        'id' => 'ceifan',
        'nome' => 'Ceifan',
        'video_id' => 'r6q8-W5m4nc',
        'nivel' => 'Avançado',
        'nivel_classe' => 'badge-danger',
        'descricao' => 'Kata avançado de nível de Faixa Preta, focado em técnicas de defesa curta, defesas duplas coordenadas contra ataques múltiplos e golpes circulares de grande potência.'
    ],
    [
        'id' => 'tsumuro',
        'nome' => 'Tsumuro',
        'video_id' => 'uWbFENyCGkc',
        'nivel' => 'Avançado',
        'nivel_classe' => 'badge-dark',
        'descricao' => 'Kata de nível superior do estilo Kenshydokan, exigindo maestria de base, transições respiratórias complexas (Ibuki) e alta explosão muscular nos contra-ataques.'
    ]
];
?>

<style>
/* Custom Style variables and global rules for Katas page */
:root {
    --primary-red: #dc3545;
    --dark-red-gradient: linear-gradient(135deg, #160303 0%, #300a0a 100%);
    --card-hover-border: rgba(220, 53, 69, 0.4);
    --card-active-shadow: 0 0 15px rgba(220, 53, 69, 0.3);
}

.hero-section {
    background: linear-gradient(135deg, #141414 0%, #290808 100%);
    color: #ffffff;
    border-radius: 16px;
    padding: 3.5rem 2rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    margin-bottom: 2.5rem;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.hero-section::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 320px;
    height: 320px;
    background: radial-gradient(circle, rgba(220, 53, 69, 0.2) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}

.theater-container {
    background: #000000;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.video-wrapper {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 */
    height: 0;
    overflow: hidden;
}

.video-wrapper iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: 0;
}

/* Card item styles */
.kata-card {
    background: #ffffff;
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid rgba(0, 0, 0, 0.07);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    cursor: pointer;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.kata-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 24px rgba(220, 53, 69, 0.12);
    border-color: var(--card-hover-border);
}

.kata-card.active-kata {
    border: 2.5px solid var(--primary-red);
    box-shadow: var(--card-active-shadow);
}

.card-thumbnail-container {
    position: relative;
    padding-bottom: 56.25%;
    height: 0;
    overflow: hidden;
    background: #111;
}

.card-thumbnail {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.kata-card:hover .card-thumbnail {
    transform: scale(1.06);
}

.play-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
}

.kata-card:hover .play-overlay {
    opacity: 1;
}

.play-btn-circle {
    width: 50px;
    height: 50px;
    background: var(--primary-red);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 1.1rem;
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.45);
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    transform: scale(0.8);
}

.kata-card:hover .play-btn-circle {
    transform: scale(1);
}

/* Filter controls */
.filter-btn {
    border-radius: 30px;
    padding: 0.5rem 1.6rem;
    font-weight: 600;
    font-size: 0.85rem;
    border: 1px solid rgba(0, 0, 0, 0.08);
    background: #ffffff;
    color: #555555;
    transition: all 0.3s ease;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
}

.filter-btn:hover {
    background: #fdfdfd;
    color: var(--primary-red);
    border-color: var(--primary-red);
}

.filter-btn.active {
    background: var(--primary-red);
    color: #ffffff;
    border-color: var(--primary-red);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.25);
}

/* Form input decoration */
#kataSearchInput {
    border: 1px solid rgba(0, 0, 0, 0.08);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
    transition: all 0.3s ease;
}

#kataSearchInput:focus {
    border-color: var(--primary-red);
    box-shadow: 0 0 10px rgba(220, 53, 69, 0.15);
    outline: none;
}

/* Smooth layout animations */
.kata-grid-item {
    animation: fadeInUp 0.5s ease backwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsividade para dispositivos móveis */
@media (max-width: 768px) {
    .hero-section {
        padding: 2.5rem 1.5rem;
    }
    .hero-section h1 {
        font-size: 2rem !important;
        line-height: 1.3;
    }
    .hero-section p {
        font-size: 1rem !important;
    }
}

@media (max-width: 480px) {
    .hero-section {
        padding: 2rem 1rem;
    }
    .hero-section h1 {
        font-size: 1.65rem !important;
        line-height: 1.25;
    }
}
</style>

<div class="container py-4">
    <!-- Hero Section -->
    <div class="hero-section text-center text-md-left d-flex align-items-center mb-5">
        <div class="w-100">
            <span class="badge badge-danger px-3 py-2 rounded-pill mb-3 text-uppercase font-weight-bold" style="font-size: 0.75rem; letter-spacing: 1px;">Treinamento Oficial</span>
            <h1 class="display-4 font-weight-bold mb-2 text-white">Katas do Estilo Kenshydokan</h1>
            <p class="lead mb-0 text-white-50">Estude as formas, as posturas e a essência técnica do nosso estilo através dos vídeos demonstrativos.</p>
        </div>
    </div>

    <!-- Theater Mode Section (Main Player) -->
    <div class="row mb-5">
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="theater-container h-100">
                <div class="video-wrapper">
                    <!-- Default loading the first Kata (Taikyoku Sono Ichi) -->
                    <iframe id="mainTheaterPlayer" src="https://www.youtube.com/embed/sE31qWpwqAs?rel=0" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-lg h-100 bg-white">
                <div class="card-body p-4 d-flex flex-column justify-content-center">
                    <div class="mb-3">
                        <span id="theaterKataNivel" class="badge badge-success px-3 py-2 rounded-pill font-weight-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Básico</span>
                    </div>
                    <h3 id="theaterKataTitle" class="font-weight-bold text-dark mb-3">Taikyoku Sono Ichi</h3>
                    <p id="theaterKataDesc" class="text-secondary leading-relaxed">Primeiro kata básico preparatório do estilo, focado em posturas iniciais de base de caminhada (Zenkutsu Dachi) e coordenação de passos com defesas baixas (Gedan Barai).</p>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                            <i class="fas fa-play"></i>
                        </div>
                        <div>
                            <span class="small text-muted d-block">Modo de Exibição</span>
                            <span class="font-weight-bold text-dark small">Teatro / Foco no Kata</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-3 border-bottom" style="gap: 15px;">
        <!-- Filters -->
        <div class="d-flex flex-wrap" style="gap: 8px;">
            <button class="filter-btn active" data-filter="todos">Todos</button>
            <button class="filter-btn" data-filter="Básico">Básico</button>
            <button class="filter-btn" data-filter="Intermediário">Intermediário</button>
            <button class="filter-btn" data-filter="Avançado">Avançado</button>
        </div>
        
        <!-- Search bar -->
        <div class="position-relative mb-2 mb-md-0" style="min-width: 280px;">
            <input type="text" id="kataSearchInput" class="form-control rounded-pill border-0 shadow-sm bg-white pl-4 pr-5 py-4" placeholder="Pesquisar Kata por nome...">
            <span class="position-absolute" style="right: 18px; top: 50%; transform: translateY(-50%); color: #aaa;">
                <i class="fas fa-search"></i>
            </span>
        </div>
    </div>

    <!-- Katas Grid -->
    <div class="row" id="katasGrid">
        <?php foreach ($katas as $index => $k): ?>
            <div class="col-md-6 col-lg-4 mb-4 kata-grid-item" data-nivel="<?php echo htmlspecialchars($k['nivel']); ?>" data-nome="<?php echo htmlspecialchars($k['nome']); ?>">
                <div class="kata-card <?php echo ($index === 0) ? 'active-kata' : ''; ?>" data-videoid="<?php echo $k['video_id']; ?>" data-nome="<?php echo htmlspecialchars($k['nome']); ?>" data-nivel="<?php echo htmlspecialchars($k['nivel']); ?>" data-nivelclass="<?php echo $k['nivel_classe']; ?>" data-descricao="<?php echo htmlspecialchars($k['descricao']); ?>">
                    <div class="card-thumbnail-container">
                        <img class="card-thumbnail" src="https://img.youtube.com/vi/<?php echo $k['video_id']; ?>/hqdefault.jpg" alt="Thumbnail do Kata <?php echo htmlspecialchars($k['nome']); ?>">
                        <div class="play-overlay">
                            <div class="play-btn-circle">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <div class="mb-2">
                            <span class="badge <?php echo $k['nivel_classe']; ?> px-2 py-1 rounded font-weight-bold text-uppercase" style="font-size: 0.65rem;"><?php echo htmlspecialchars($k['nivel']); ?></span>
                        </div>
                        <h5 class="font-weight-bold text-dark mb-2"><?php echo htmlspecialchars($k['nome']); ?></h5>
                        <p class="text-muted small leading-relaxed mb-0 flex-grow-1">
                            <?php 
                                $desc = htmlspecialchars($k['descricao']);
                                echo (strlen($desc) > 110) ? substr($desc, 0, 107) . '...' : $desc; 
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Alternar vídeos no player principal
    const mainTheaterPlayer = document.getElementById('mainTheaterPlayer');
    const theaterKataTitle = document.getElementById('theaterKataTitle');
    const theaterKataDesc = document.getElementById('theaterKataDesc');
    const theaterKataNivel = document.getElementById('theaterKataNivel');
    const kataCards = document.querySelectorAll('.kata-card');

    kataCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remove active style from all cards
            kataCards.forEach(c => c.classList.remove('active-kata'));
            
            // Add active style to clicked card
            this.classList.add('active-kata');

            // Get data attributes
            const videoId = this.getAttribute('data-videoid');
            const nome = this.getAttribute('data-nome');
            const nivel = this.getAttribute('data-nivel');
            const nivelClass = this.getAttribute('data-nivelclass');
            const descricao = this.getAttribute('data-descricao');

            // Update iframe src with autoplay
            mainTheaterPlayer.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`;

            // Update details
            theaterKataTitle.textContent = nome;
            theaterKataDesc.textContent = descricao;
            
            // Update badge text
            theaterKataNivel.textContent = nivel;
            
            // Clean previous badge classes and add the new one
            theaterKataNivel.className = 'badge px-3 py-2 rounded-pill font-weight-bold text-uppercase';
            nivelClass.split(' ').forEach(cls => {
                if (cls.trim()) {
                    theaterKataNivel.classList.add(cls);
                }
            });

            // Smooth scroll to the theater player container
            const theaterSection = document.querySelector('.theater-container');
            theaterSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    });

    // 2. Filtros de categoria (Básico, Intermediário, Avançado)
    const filterButtons = document.querySelectorAll('.filter-btn');
    const gridItems = document.querySelectorAll('.kata-grid-item');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active filter button
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const filterValue = this.getAttribute('data-filter');

            gridItems.forEach(item => {
                const itemNivel = item.getAttribute('data-nivel');
                
                // Tratar "Avançado" de forma que pegue tanto "Avançado" quanto "Avançado / Faixa Preta"
                let isMatch = false;
                if (filterValue === 'todos') {
                    isMatch = true;
                } else if (filterValue === 'Avançado') {
                    isMatch = itemNivel.includes('Avançado');
                } else {
                    isMatch = (itemNivel === filterValue);
                }

                if (isMatch) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // 3. Barra de busca
    const searchInput = document.getElementById('kataSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, ""); // Remove acentos
            const activeFilter = document.querySelector('.filter-btn.active').getAttribute('data-filter');

            gridItems.forEach(item => {
                const itemNome = item.getAttribute('data-nome').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, "");
                const itemNivel = item.getAttribute('data-nivel');

                let matchesFilter = false;
                if (activeFilter === 'todos') {
                    matchesFilter = true;
                } else if (activeFilter === 'Avançado') {
                    matchesFilter = itemNivel.includes('Avançado');
                } else {
                    matchesFilter = (itemNivel === activeFilter);
                }

                const matchesSearch = itemNome.includes(query);

                if (matchesFilter && matchesSearch) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
});
</script>

<?php include 'rodape.php'; ?>