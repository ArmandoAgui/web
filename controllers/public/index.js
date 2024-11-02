const activate = document.getElementById("activate");


var alertPlaceholder = document.getElementById('liveAlertPlaceholder')
var alertTrigger = document.getElementById('liveAlertBtn')

function alert(message, type) {
    var wrapper = document.createElement('div')
    wrapper.innerHTML = '<div class="alert alert-' + type + ' alert-dismissible" role="alert">' + message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'

    alertPlaceholder.append(wrapper)
}

if (alertTrigger) {
    alertTrigger.addEventListener('click', function () {
        alert('Nice, you triggered this alert message!', 'success')
    })
}


//Carouseles
const popularProdyctsData = [["AssassinsCreedFigure2.jpg", 5, "Assassins Creed", "Figure", "$14.99"],
["GenshinImpactFigure1.png", 4, "Genshin Impact", "Figure", "$14.99"],
["HaloFigure1.png", 3, "Master Chief", "Figure", "$14.99"],
["JujutsuKaisenFigure1.png", 2, "Jujutsu Kaisen", "Figure", "$14.99"],
["AssassinsCreedFigure2.jpg", 1, "Assassins Creed", "Figure", "$14.99"],
["GenshinImpactFigure1.png", 2, "Genshin Impact", "Figure", "$14.99"],
["HaloFigure1.png", 3, "Master Chief", "Figure", "$14.99"],
["JujutsuKaisenFigure1.png", 4, "Jujutsu Kaisen", "Figure", "$14.99"]]

//Fill carrusel
const fillCarousel = (carousel) =>{
    if (carousel !== null) {
        for (let i = 0; i < 8; i++) {
            carousel.insertAdjacentHTML('beforeend', `
            <div class="item">
                <div class="product-content">
                    <article class="col-4 product m-0 overflow-hidden">
                        <img class="img-product position-absolute" src="../../resources/img/products/${popularProdyctsData[i][0]}" alt="">
                        <div class="elements">
                            <div class="stars d-flex flex-row align-items-center justify-content-center m-0 overflow-hidden">
                                <p class="fs-5 m-0 me-1">${popularProdyctsData[i][1]}</p>
                                <img src="../../resources/img/public_icons/star1.png" alt="">
                            </div>
                            <p class="add-cart d-flex justify-content-center align-items-center m-0 opacity-0">Añadir a carrito</p>
                            <p class="preview text-white d-flex justify-content-center align-items-center m-0 opacity-0">Vista previa</p>
                        </div>
                        <div class="text-product p-3">
                            <h3 class="m-0"><a href="pages/product.html" class="text-decoration-none text-black">${popularProdyctsData[i][2]}</a></h3>
                            <div class="d-flex justify-content-between">
                                <p>${popularProdyctsData[i][3]}</p>
                                <p>${popularProdyctsData[i][4]}</p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
            `)
        }
    }
}

//Carousel options
const carouselOPtions = (carousel)=>{
    $(carousel).owlCarousel({
        loop: true, margin: 5, responsiveClass: true, autoplay: true, autoplayTimeout:5000,
        autoplayHoverPause: true, nav: true, dots: false,
        responsive: {
            0: {
                items: 1
            },
            576: {
                items: 2
            },
            768: {
                items: 2
            },
            1200: {
                items: 4
            }
        }
    })
}


//Home carousel
const homeCarousel = document.getElementById("homeOwlCarousel")
fillCarousel(homeCarousel)

carouselOPtions("#homeOwlCarousel")

//relatedCarousel
const relatedCarousel = document.getElementById("relatedOwlCarousel")
fillCarousel(relatedCarousel)

carouselOPtions("#relatedOwlCarousel")