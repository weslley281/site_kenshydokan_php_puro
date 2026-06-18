<?php
include "menu.php";
include_once "../models/galeriaModel.php";

$galeriaModel = new Galeria();
$galleries_data = $galeriaModel->buscarGaleriasComFotos();
?>

<style>
    .gallery-image {
        cursor: pointer;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .gallery-image:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 16px rgba(0,0,0,0.3);
    }

    /* Estilos para os botões de navegação do modal */
    .modal-body {
        position: relative;
        padding: 0;
    }
    .modal-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0,0,0,0.4);
        color: white;
        border: none;
        font-size: 2rem;
        font-weight: bold;
        cursor: pointer;
        height: 100%;
        width: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.2s ease;
    }
    .modal-nav-btn:hover {
        background-color: rgba(0,0,0,0.7);
    }
    .modal-nav-btn.prev {
        left: 0;
    }
    .modal-nav-btn.next {
        right: 0;
    }
</style>

<br>

<!-- --- 2. O HTML agora é um template Vue --- -->
<div id="galleryApp" class="container-fluid">

    <div v-for="(gallery, galleryIndex) in galleries" :key="gallery.id_galeria" class="container mt-5">
        <div class="text-center">
            <h2><strong>{{ gallery.nome }}</strong></h2>
        </div>
        <div class="row mt-5">
            <div v-for="(photo, photoIndex) in gallery.photos" :key="photo.id_foto" class="col-md-4 mb-3">
                <!-- Passamos os índices para o método showImage -->
                <img :src="'../slides/' + photo.foto" class="img-thumbnail gallery-image" :alt="photo.foto" @click="showImage(galleryIndex, photoIndex)">
            </div>
        </div>
    </div>

    <!-- Modal para visualização de imagens (controlado pelo Vue) -->
    <div class="modal" id="imageModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <!-- Botão Anterior -->
                    <button class="modal-nav-btn prev" @click.stop="previousImage">&#10094;</button>
                    
                    <img class="img-fluid" :src="modalImageUrl" alt="Imagem em destaque">
                    
                    <!-- Botão Próximo -->
                    <button class="modal-nav-btn next" @click.stop="nextImage">&#10095;</button>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include "rodape.php";?>

<!-- --- 3. O JavaScript/Vue que controla tudo --- -->
<script>
    const galleriesData = <?php echo json_encode($galleries_data); ?>;

    const { createApp } = Vue;

    createApp({
        data() {
            return {
                galleries: galleriesData,
                allPhotos: [],
                currentIndex: null
            }
        },
        computed: {
            modalImageUrl() {
                if (this.currentIndex === null || !this.allPhotos[this.currentIndex]) {
                    return '';
                }
                return '../slides/' + this.allPhotos[this.currentIndex].foto;
            }
        },
        methods: {
            showImage(galleryIndex, photoIndex) {
                // Calcula o índice absoluto na lista de todas as fotos
                let flatIndex = 0;
                for (let i = 0; i < galleryIndex; i++) {
                    flatIndex += this.galleries[i].photos.length;
                }
                flatIndex += photoIndex;

                this.currentIndex = flatIndex;
                $('#imageModal').modal('show');
            },
            nextImage() {
                if (this.currentIndex === null) return;
                this.currentIndex = (this.currentIndex + 1) % this.allPhotos.length;
            },
            previousImage() {
                if (this.currentIndex === null) return;
                this.currentIndex = (this.currentIndex - 1 + this.allPhotos.length) % this.allPhotos.length;
            },
            createFlatPhotoList() {
                this.allPhotos = this.galleries.reduce((acc, gallery) => acc.concat(gallery.photos), []);
            }
        },
        created() {
            // Cria a lista única de fotos quando o app é iniciado
            this.createFlatPhotoList();
        }
    }).mount('#galleryApp');
</script>