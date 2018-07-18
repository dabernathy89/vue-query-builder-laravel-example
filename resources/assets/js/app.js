
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueQueryBuilder from "vue-query-builder";

const app = new Vue({
    el: '#app',

    components: { VueQueryBuilder },

    methods: {
        updateQuery: function(value) {
          this.output = value;
        },

        getResults: function() {
            axios.post('/results', {query: this.output}).then(response => {
                this.results = response.data;
                console.log({ response });
            });
        }
    },

    data() {
        return {
            output: null,
            results: [],
            rules: [
                {
                    type: "numeric",
                    id: "age",
                    label: "Age"
                },
                {
                    type: "select",
                    id: "job_title",
                    label: "Job Title",
                    choices: [
                        {label: 'Regional Manager', value: 'Regional Manager'},
                        {label: 'Assistant to the Regional Manager', value: 'Assistant to the Regional Manager'},
                        {label: 'Sales Associate', value: 'Sales Associate'},
                    ]
                },
            ],
            initialQuery: {
              "logicalOperator": "All",
              "children": [
                {
                  "type": "query-builder-rule",
                  "query": {
                    "rule": "age",
                    "selectedOperator": ">",
                    "selectedOperand": "Age",
                    "value": "50"
                  }
                },
                {
                  "type": "query-builder-group",
                  "query": {
                    "logicalOperator": "Any",
                    "children": [
                      {
                        "type": "query-builder-rule",
                        "query": {
                          "rule": "job_title",
                          "selectedOperator": "=",
                          "selectedOperand": "Job Title",
                          "value": "Regional Manager",
                        }
                      },
                      {
                        "type": "query-builder-rule",
                        "query": {
                          "rule": "job_title",
                          "selectedOperator": "=",
                          "selectedOperand": "Job Title",
                          "value": "Assistant to the Regional Manager",
                        }
                      }
                    ]
                  }
                }
              ]
            }
        };
    },
});
