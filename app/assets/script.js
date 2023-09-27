/**
 * Clase CustomForm: esta clase extiende HTMLElement y representa un formulario personalizado.
 * El formulario tiene funcionalidades para cargar dinámicamente opciones de selección
 * basadas en las selecciones anteriores y enviar datos del formulario mediante una solicitud POST.
 * También maneja eventos como cambios en la selección de la región y el envío del formulario.
 */
class CustomForm extends HTMLElement {
    constructor() {
        super();
        this.init(); // Inicializa la clase

        // Encuentra el formulario dentro del elemento personalizado
        this.form = this.querySelector('form');

        // Encuentra el elemento de selección de región
        this.region = this.form.querySelector('#region');

        // Agrega un event listener para detectar cambios en la selección de región
        if (this.region) {
            this.region.addEventListener('change', async (e) => {
                const region = e.target.value;
                const response = await fetch(`php/information.php?region=${region}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const html = await response.json();
    
                const dataComunas = html?.comunas ?? []
    
                this.comunas = dataComunas;
            });
        }

        // Agrega un event listener para manejar el envío del formulario
        if (this.form) {
            this.form.addEventListener('submit', (e) => {
                e.preventDefault();
                const formData = new FormData(e.target);

                // Convierte los valores de "with_us" en una cadena separada por comas
                formData.set("with_us", formData.getAll('with_us[]').join(','));

                // Realiza una solicitud POST para enviar el formulario
                fetch('php/submit.php', {
                    method: 'POST',
                    body: formData,
                })
                    .then(response => response.json())
                    .then(data => {

                        if (data.success) e.target.reset();
                        alert(data.message);
                        
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al enviar el formulario');
                    });
            });
        }
    }

    // Establece las opciones para el elemento de selección de candidatos
    set candidates(dataCandidates) {
        const candidates = this.form.querySelector('#candidate');
        candidates.innerHTML = '';
        if(candidates && dataCandidates.length > 0){
            dataCandidates.forEach(candidato => {
                this.option =  candidato;
                candidates.appendChild(this.option);
            });
        }
    }

    // Establece las opciones para el elemento de selección de estados
    set states(dataStates) {
        const regiones = this.form.querySelector('#state');
        regiones.innerHTML = '';
        if(regiones && dataStates.length > 0){
            dataStates.forEach(state => {
                this.option =  state;
                regiones.appendChild(this.option);
            });
        }
    }

    // Establece las opciones para el elemento de selección de provincias
    set provinces(dataProvinces) {
        const provinces = this.form.querySelector('#province');
        provinces.innerHTML = '';
        if(provinces && dataProvinces.length > 0){
            dataProvinces.forEach(province => {
                this.option =  province;
                provinces.appendChild(this.option);
            });
        }
    }

    // Crea una opción para un elemento de selección
    set option(info) {
        const option = document.createElement('option');
        option.value = info.id;
        option.textContent = info.name;
        this._option = option; 
    }

    // Obtiene una opción
    get option() {
        return this._option;
    }

    // Inicializa la clase cargando datos iniciales y opciones de selección
    async init() {
        try {
            const response = await fetch('php/information.php');
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const html = await response.json();
            console.log(html)
            const dataCandidates = html?.candidates ?? []
            const dataStates = html?.states ?? []
            const dataProvinces = html?.provinces ?? []
            this.states = dataStates;
            this.provinces = dataProvinces;
            this.candidates = dataCandidates;
            

        } catch (error) {
            console.log(error)
        }
    }
}

// Define el elemento personalizado 'custom-form' y lo registra en el DOM
document.addEventListener('DOMContentLoaded', function () {
    if (!customElements.get('custom-form')) {
        customElements.define('custom-form', CustomForm);
    }
});