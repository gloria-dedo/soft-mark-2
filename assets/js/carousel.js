const slides = document.querySelectorAll(".slide");

let current = 0;

function showSlide(index) {
    slides.forEach(slide => {
        slide.classList.remove("active");
    });

    slides[index].classList.add("active");
}

document.addEventListener("click", (e) => {

    if (e.target.classList.contains("next")) {

        current++;

        if(current >= slides.length){
            current = 0;
        }

        showSlide(current);
    }

    if (e.target.classList.contains("prev")) {

        current--;

        if(current < 0){
            current = slides.length - 1;
        }

        showSlide(current);
    }

});

setInterval(() => {

    current++;

    if(current >= slides.length){
        current = 0;
    }

    showSlide(current);

}, 5000);