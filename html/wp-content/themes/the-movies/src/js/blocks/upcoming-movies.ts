import Glide from "@glidejs/glide";

const init = () => {
    console.log("init upcoming movies");
    (new Glide(".block__upcoming-movies__movies")).mount();
}

export default init;