<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 align-left">

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

                <button class="button" @click="submitList">
                    Submit List
                </button>

            </div>

            <div v-if="error_saving" class="col-md-8 align-left error">
                <strong>{{ error_saving }}</strong>
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
            error_saving: false
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

        submitList()
        {
            var peopleModified = this.stripVueKey(this.people);

            this.saving = true;

            axios.post('/api/v1/people', {
                people: JSON.stringify(peopleModified)
            }).then((response) => {

            }).catch((err) => {
                console.error(err);
                this.error_saving = err;
            });
        }
    },

    created() {

    }
}
</script>

<style lang="scss">
.align-left {
    text-align: left;
}

.error {
    color: #a00;
}
</style>