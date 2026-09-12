const cepInput = document.querySelector("#cep");

const enderecoInput = document.querySelector("#endereco");
const bairroInput = document.querySelector("#bairro");
const cidadeInput = document.querySelector("#cidade");
const estadoInput = document.querySelector("#estado");

cepInput.addEventListener("input", function () {

    let cep = cepInput.value.replace(/\D/g, "");

    if (cep.length > 8) {
        cep = cep.substring(0, 8);
    }

    if (cep.length > 5) {
        cepInput.value =
            cep.substring(0, 5) + "-" + cep.substring(5);
    } else {
        cepInput.value = cep;
    }
});


cepInput.addEventListener("blur", function () {

    const cep = cepInput.value.replace(/\D/g, "");

    if (cep.length !== 8) {
        return;
    }

    enderecoInput.value = "Consultando...";
    bairroInput.value = "Consultando...";
    cidadeInput.value = "Consultando...";

    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(function (resposta) {

            if (!resposta.ok) {
                throw new Error("Erro na consulta do CEP.");
            }

            return resposta.json();
        })

        .then(function (dados) {

            if (dados.erro) {
                alert("CEP não encontrado.");

                enderecoInput.value = "";
                bairroInput.value = "";
                cidadeInput.value = "";
                estadoInput.value = "";

                return;
            }

            enderecoInput.value = dados.logradouro || "";
            bairroInput.value = dados.bairro || "";
            cidadeInput.value = dados.localidade || "";
            estadoInput.value = dados.uf || "";

        })

        .catch(function (erro) {

            console.error(erro);

            alert("Não foi possível consultar o CEP.");

            enderecoInput.value = "";
            bairroInput.value = "";
            cidadeInput.value = "";
            estadoInput.value = "";
        });
});