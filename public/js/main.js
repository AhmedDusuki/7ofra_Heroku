

let x = document.getElementById("myNav");
function responsive() {
    if (x.className === "overlay") {
      x.classList.add("impo");
    } else {
      x.className = "overlay";
    }
}
document.getElementById("clop").addEventListener("click",responsive);
window.addEventListener('resize',()=> x.className = "overlay");

let cont=document.getElementById("content");

let asst=document.getElementById("assets");
if (cont == null){

}
else{
    cont.addEventListener("click",(event)=>{
        event.preventDefault();
        document.getElementById("main").scrollIntoView({behavior: "smooth", block: "start"});
        x.className = "overlay";
    });
}

if (asst == null){

}
else{
    asst.addEventListener("click",(event)=>{
        event.preventDefault();
        document.getElementById("main2").scrollIntoView({behavior: "smooth", block: "start"});
        x.className = "overlay";
    });
}
/////////////// the map code////////////////////////


