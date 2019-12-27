
class MobileDropDown {
    constructor() {
        window.onload = () => {
            if (window.innerWidth <= 1024) {
                this.linkClickListener()
                this.opened = {}
            }
        }
    }

    linkClickListener() {

        $('.link').click((e) => {

            const linkDropdown = $(e.currentTarget).find('.link__dropdown');
            const linkHref = e.target.getAttribute("href");
            const linkClass = e.target.getAttribute("class");


            if (linkDropdown.length > 0 && linkClass === "link__main-link") {
                e.preventDefault();
            } else {
                return
            }


            if (this.opened[e.target]) {
                linkDropdown.css("display", "none");
                this.opened[e.target] = false;
            } else {
                this.opened[e.target] = true;
                 linkDropdown.css("display", "block");
            }


        });
    }
}


export default MobileDropDown;