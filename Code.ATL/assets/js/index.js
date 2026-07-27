// References to DOM elements
const prevBtn = document.querySelector("#prev");
const nextBtn = document.querySelector("#next");
const book = document.querySelector("#book");

const p1 = document.querySelector("#p1");
const p2 = document.querySelector("#p2");
const p3 = document.querySelector("#p3");
const p4 = document.querySelector("#p4");
const p5 = document.querySelector("#p5");
const p6 = document.querySelector("#p6");
const p7 = document.querySelector("#p7");
const p8 = document.querySelector("#p8");


// Business logic / State tracking
let currentLocation = 1;
let numOfPapers = 8;
let maxLocation = numOfPapers + 1;

nextBtn.addEventListener("click", goNext);
prevBtn.addEventListener("click", goPrev);

function openBook() {
     book.style.transform = "translateX(50%)";
    document.body.classList.add("has-opened");
}

function closeBook(isAtBeginning) {
   document.body.classList.remove("has-opened");

    if (isAtBeginning) {
        book.style.transform = "translateX(0%)";
    } else {
        book.style.transform = "translateX(100%)";
    }
}

function goNext() {
    if (currentLocation < maxLocation) {
        switch (currentLocation) {
            case 1:
                openBook(); // Center-align book when first page flips
                p1.classList.add("flipped");
                p1.style.zIndex = 1;
                break;
            case 2:
                p2.classList.add("flipped");
                p2.style.zIndex = 2;
                break;
            case 3:
                p3.classList.add("flipped");
                p3.style.zIndex = 3;
                break;
            case 4:
                p4.classList.add("flipped");
                p4.style.zIndex = 4;
                break;
            case 5:
                p5.classList.add("flipped");
                p5.style.zIndex = 5;
                break;
            case 6:
                p6.classList.add("flipped");
                p6.style.zIndex = 6;
                break;
            case 7:
                p7.classList.add("flipped");
                p7.style.zIndex = 7;
                break;
            case 8:
                p8.classList.add("flipped");
                p8.style.zIndex = 8;
                closeBook(false);
                break;
            default:
                throw new Error("Unknown state");
        }
        currentLocation++;
    }
}

function goPrev() {
    if (currentLocation > 1) {
        currentLocation--;
        switch (currentLocation) {
            case 1:
                p1.classList.remove("flipped");
                p1.style.zIndex = 8;
                closeBook(true); // Move book back to the start position
                break;
            case 2:
                p2.classList.remove("flipped");
                p2.style.zIndex = 7;
                break;
            case 3:
                p3.classList.remove("flipped");
                p3.style.zIndex = 6;
                break;
            case 4:
                p4.classList.remove("flipped");
                p4.style.zIndex = 5;
                break;
            case 5:
                p5.classList.remove("flipped");
                p5.style.zIndex = 4;
                break;
            case 6:
                p6.classList.remove("flipped");
                p6.style.zIndex = 3;
                break;
            case 7:
                p7.classList.remove("flipped");
                p7.style.zIndex = 2;
                break;
            case 8:
                p8.classList.remove("flipped");
                p8.style.zIndex = 1;
                openBook()
                break;
            default:
                throw new Error("Unknown state");
        }
    }
}