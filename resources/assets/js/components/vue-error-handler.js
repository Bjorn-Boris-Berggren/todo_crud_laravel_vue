import Vue from 'vue';

Vue.config.errorHandler = (err, vm, info) => {
        alert("503 Service Unavailable");
};



    