var ktory = 1
var dawny
var pani=false
var pd=false
var a
var p=[];
for(var i=0; i<8; i++)
{
    p[i]=false;
}

var dnioZmieniacz=document.getElementById("dnioZmieniacz")
var dnioZmieniaczowaLista=document.getElementById("dnioZmieniaczowaLista")
var pdpokaz=document.getElementById("pdpokaz")
var pdlist=document.getElementById("pdnajutro")
var yesteButton=document.getElementById("yesteButton")
var tommoButton=document.getElementById("tommoButton")


function axa()
{
    ktorystr="box"+ktory
    console.log(ktory)
    document.getElementById(ktorystr).classList.add("mobileBlock")
    document.getElementById(dawny).classList.remove("onlyDesktop")
    document.getElementById(dawny).classList.remove("mobileBlock")
    document.getElementById(dawny).classList.add("onlyDesktop")
}

dnioZmieniacz.onclick = function()
{
    pani=!pani
    if(pani)
        dnioZmieniaczowaLista.style.display="block"
    else
        dnioZmieniaczowaLista.style.display="none"
}


pdpokaz.onclick = function()
{
    pd=!pd
    if(pd)
        pdlist.classList.remove("znikniete")
    else
        pdlist.classList.add("znikniete")
}

function lekcjoZmieniacz(a)
{
    //console.log("gag")
    var nazwa="lekcjoZmieniaczowaLista";
    var nazwaS="lekcjoZmieniaczowaListaSala";
    nazwa+=a;
    nazwaS+=a;
    p[a]=!p[a]
    if(p[a])
    {
        document.getElementById(nazwa).style.display="block"
        document.getElementById(nazwaS).style.display="block"
        //console.log("gaga")
    }
    else
    {
        document.getElementById(nazwa).style.display="none"
        document.getElementById(nazwaS).style.display="none"
    }
}


/*yesteButton.onclick = function()
{
    if(ktory==1)
    {
        ktory=ktory-1
        dawny="box1"
    }
    else if(ktory>1)
    {
        ktory=ktory-1
        dawny="box2"
    }
    else
        return 0
    axa()
}

tommoButton.onclick = function()
{
    if(ktory==1)
    {
        ktory=ktory+1
        dawny="box1"
    }
    else if(ktory<1)
    {
        ktory=ktory+1
        dawny="box0"
    }
    else
        return 0
    axa()
}*/