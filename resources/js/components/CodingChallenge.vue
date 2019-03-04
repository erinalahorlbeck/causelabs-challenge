<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 align-left mb-2">

                <div v-for="person in people" :key="person.vue_key">

                    <person :person="person"
                        :editable="isEditable(person)"
                        @input="updatePerson">
                    </person>

                    <button v-if="isEditable(person)" class="button" @click="removePerson(person)">
                        Remove
                    </button>

                    <hr>
                </div>

                <button class="button" @click="addPerson">
                    Add Person
                </button>

                <button v-if="!saving" class="button" @click="submitList">
                    Submit List
                </button>

                <button v-if="saving" class="button" disabled="disabled">
                    Submitting...
                </button>

                <span v-if="success_saving" class="success">
                    {{ success_saving }}
                </span>

                <span v-if="error_saving" class="error">
                    <strong>{{ error_saving }}</strong>
                </span>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            people: [
                {
                    "vue_key": 1,
                    "first_name": "cody",
                    "last_name": "duder",
                    "age": 38,
                    "email": "codyduder@causelabs.com",
                    "secret": "VXNlIHRoaXMgc2VjcmV0IHBocmFzZSBzb21ld2hlcmUgaW4geW91ciBjb2RlJ3MgY29tbWVudHM="
                },
                {
                    "vue_key": 2,
                    "first_name": "ladee",
                    "last_name": "linter",
                    "age": 99,
                    "email": "lindaladee@causelabs.com",
                    "secret": "cmVzb3VyY2UgdmlvbGF0aW9u"
                },
            ],

            // This counter should never be decremented, even if a person
            // is removed from the list. It is used to assign a temporary
            // "primary key" on the frontend only for Vue to track as a
            // key in the v-for loop.
            peopleCount: 2,

            saving: false,
            error_saving: false,
            success_saving: false
        }
    },

    methods: {

        /**
         * Add a person to the list.
         * 
         * @return {Void}
         */
        addPerson()
        {
            this.peopleCount++;
            this.people.push({
                vue_key: this.peopleCount,
                first_name: '',
                last_name: '',
                age: 0,
                email: '',
                secret: ''
            });
        },

        /**
         * Update the person based on the child component's event data.
         * (Used to avoid mutating the component's props in the child
         * component)
         * 
         * @param {Object} data   Will contain { vue_key, attrib, value }
         * @return {Void}
         */
        updatePerson(data)
        {
            var attrib = data.attrib;
            var person = this.people.find(person => data.vue_key === person.vue_key);
            person[attrib] = data.value;
        },

        /**
         * Remove the person from the list. As per the coding challenge,
         * there is no requirement to delete in the database, only on
         * the frontend.
         * 
         * @param {Object} person
         * @return {Void}
         */
        removePerson(person)
        {
            var index = this.people.findIndex( iperson => iperson.vue_key === person.vue_key );
            this.people.splice(index, 1);
        },

        /**
         * @param {Object} person
         * @return {Boolean}
         */
        isEditable(person)
        {
            return person.vue_key > 2
        },

        /**
         * Get the final data to submit to the API.
         * 
         * @return  {String} Stringified JSON data
         */
        getSubmitData()
        {
            var peopleCollection = collect(this.people);

            // This ensures we submit data without the `vue_key` prop
            peopleCollection.transform((person) => {
                return {
                    "first_name": person.first_name,
                    "last_name": person.last_name,
                    "age": person.age,
                    "email": person.email,
                    "secret": btoa(person.secret)
                }
            });

            return {
                people: JSON.stringify({
                    data: peopleCollection.toArray()
                })
            };
        },

        submitList()
        {
            this.error_saving = false;
            this.saving = true;

            var data = this.getSubmitData();

            axios.post('/api/v1/people', data).then((response) => {
                this.success_saving = 'Saved!';
                var t = setTimeout(() => {
                    this.success_saving = false;
                }, 2250);
            }).catch((err) => {
                console.error(err);
                this.error_saving = err;
            }).then(() => {
                // always executed
                this.saving = false;
            });
        }
    },

    created() {

    }
}
</script>

<style lang="scss">
    .mb-2 { margin-bottom: 2rem; }
    .align-left { text-align: left; }
    .success, .error { float: right; }
    .success { font-weight: bold; color: #050; }
    .error { color: #a00; }
</style>