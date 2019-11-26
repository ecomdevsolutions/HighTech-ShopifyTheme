

class ImageMagnify {
    constructor(img, zoom) {
        this.img = img
        this.glass = null
        this.w = null
        this.h = null
        this.bw = null;
        this.zoom = zoom;
        this.magnify();

    }
  magnify() {

        var glass, w, h, bw;


        /*create magnifier glass:*/
        glass = document.createElement("DIV");
        glass.setAttribute("class", "img-magnifier-glass");
        glass.setAttribute("style", "z-index: 2")
        glass.style.visibility = "hidden";
        /*insert magnifier glass:*/
        this.img.parentElement.insertBefore(glass, this.img);
        this.img.parentElement.insertBefore(glass, this.img);
        /*set background properties for the magnifier glass:*/
        glass.style.backgroundImage = "url('" + this.img.src + "')";
        glass.style.backgroundRepeat = "no-repeat";
        glass.style.backgroundSize = (this.img.width * this.zoom) + "px " + (this.img.height * this.zoom) + "px";
        bw = 3;
        w = glass.offsetWidth / 2;
        h = glass.offsetHeight / 2;
        /*execute a function when someone moves the magnifier glass over the image:*/
        glass.addEventListener("mousemove", (e) => {
           this.moveMagnifier(e)
        });
        this.img.addEventListener("mousemove", (e) => {
            this.moveMagnifier(e)
        });
        /*and also for touch screens:*/
        glass.addEventListener("touchmove", (e) => {
             this.moveMagnifier(e)
        });
        this.img.addEventListener("touchmove", (e) => {
             this.moveMagnifier(e)
        });



        this.glass = glass;
        this.h = h;
        this.w = w;
        this.bw = bw;

  }

  hide() {
    this.glass.style.display = "none";
  }

  show() {
    this.glass.style.display = "block";
  }

  moveMagnifier(e) {
    var pos, x, y;
    /*prevent any other actions that may occur when moving over the image*/
    e.preventDefault();
    /*get the cursor's x and y positions:*/

    pos = this.getCursorPos(e)


    x = pos.x;
    y = pos.y;
    /*prevent the magnifier glass from being positioned outside the image:*/
    if (x > this.img.width - (this.w / this.zoom)) {x = this.img.width - (this.w / this.zoom);}
    if (x < this.w / this.zoom) {x = this.w / this.zoom;}
    if (y > this.img.height - (this.h / this.zoom)) {y = this.img.height - (this.h / this.zoom);}
    if (y < this.h / this.zoom) {y = this.h / this.zoom;}
    /*set the position of the magnifier glass:*/
    this.glass.style.left = (x - this.w) + "px";
    this.glass.style.top = (y - this.h) + "px";
    /*display what the magnifier glass "sees":*/
    this.glass.style.backgroundPosition = "-" + ((x * this.zoom) - this.w + this.bw) + "px -" + ((y * this.zoom) - this.h + this.bw) + "px";
    this.glass.style.visibility = "visible";
  }

  getCursorPos(e) {
    var a, x = 0, y = 0;
    e = e || window.event;
    /*get the x and y positions of the image:*/
    a = this.img.getBoundingClientRect();
    /*calculate the cursor's x and y coordinates, relative to the image:*/
    x = e.pageX - a.left;
    y = e.pageY - a.top;
    /*consider any page scrolling:*/
    x = x - window.pageXOffset;
    y = y - window.pageYOffset;
    return {x : x, y : y};
  }
}
export default ImageMagnify;