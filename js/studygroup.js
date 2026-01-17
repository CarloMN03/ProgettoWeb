function generaEsami(esami){
    let opt = `
        <label for="idesame" name="idesame">Esame: </label><select name="idesame" id="idesame">
        `
    for (let i = 0 ; i < esami.length; i++){
        opt += `
            <option value="${esami[i]["idesame"]}">${esami[i]["nomeesame"]}</option>
        `
    }
    opt += `
        </select>
    `
    return opt;  
}

async function addEsami(){
    const cdl = document.getElementById("idcdl").value;
    const url = 'api-listaesami.php';
    const formData = new FormData();
    formData.append('idcdl', cdl);
    try {
        const response = await fetch(url, {
            method: "POST",
            body: formData
        });

        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        console.log(json);
        const listaesami = generaEsami(json);
        const main = document.querySelector(".cerca-sg form ul li:nth-of-type(2)");
        main.innerHTML = listaesami;
       
    } catch (error) {
        console.log(error.message);
    }
}
