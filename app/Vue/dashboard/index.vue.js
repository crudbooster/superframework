const template = `
    
`;

export default {
    name: "dashboard",
    template: template,
    beforeCreate() {
        this.$parent.guard()
    },
    data() {
        return {

        }
    },
    methods: {

    }
}
