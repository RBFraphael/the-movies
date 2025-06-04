import Glide from "@glidejs/glide";

const init = () => {
    const carousel = new Glide(".block__upcoming-movies__movies", {
        autoplay: 5000,
        hoverpause: true,
        peek: {
            before: 100,
            after: 100
          }
    });
    carousel.mount();
}

export default init;