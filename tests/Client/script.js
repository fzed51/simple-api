(() => {
    fetch("https://jsonplaceholder.typicode.com/todos/1")
        .then(response => response.text())
        .then(text => document.querySelector("#body").value = text);

    const send = event => {
        const uri = document.querySelector("#uri").value;
        const client = document.querySelector("#client").value;
        const methode = document.querySelector("#methode").value;
        const path = document.querySelector("#path").value;
        const body = document.querySelector("#body").value;
        console.debug("uri", uri);
        console.debug("methode", methode);
        console.debug("path", path);
        methode == "POST" && console.debug("body", JSON.parse(body));

        fetch(
            uri + path,
            methode !== "POST"
                ? {
                    headers: {
                        Accept: "application/json",
                        Authorization: 'Bearer ' + client
                    },
                    method: methode
                }
                : {
            headers: {
            Accept: "application/json",
            Authorization: 'Bearer ' + client,
            "Content-Type": "application/json"
            },
            method: methode,
            body
                }
        )
            .then(response => response.json())
            .then(data => {
                console.dir(data);
            });
    };

    document.querySelector("#btn-send").addEventListener("click", send);
})();
