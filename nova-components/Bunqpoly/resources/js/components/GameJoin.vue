<template>
    <div>
        <div class="flex justify-between">
            <div class="relative h-9 mb-6">
                <heading class="mb-6">Join lobby {{ this.game.name }} <span class="text-sm">(id: {{ this.game_id }}) ${{ this.game.price }}</span></heading>
            </div>
            <span class="ml-auto mb-6">
                <button type="submit" @click="startGame()" class="btn btn-default btn-primary" :disabled="usersPayed" :class="{ 'opacity-50 cursor-not-allowed': usersPayed }">Start Game</button>
            </span>
        </div>

        <div class="overflow-hidden overflow-x-auto relative bg-white">
            <table cellpadding="0" cellspacing="0" data-testid="resource-table" class="table w-full">
                <thead>
                <tr>
                    <th class="text-left"><span class="cursor-pointer inline-flex items-center">ID</span></th>
                    <th class="text-left"><span>name</span></th>
                    <th class="text-left"><span>payed</span></th>
                </tr>
                </thead>
                <tbody>
                    <tr v-for="user in game.users">
                        <td><span class="whitespace-no-wrap text-left">{{ user.id }}</span></td>
                        <td><span class="whitespace-no-wrap text-left">{{ user.name }}</span></td>
                        <td><div class="flex items-center text-left">
                            <font-awesome-icon :icon="getIcon(user.pivot.payment_status)" :spin="getIcon(user.pivot.payment_status) === 'spinner'" size="lg"/>
                        </div></td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                game_id: this.$route.params.id,
                game: {
                    name: "",
                    users: []
                },
                is_creator: false,
            }
        },

    // {{ user.pivot.payment_status }}
        computed: {
            usersPayed() {
                let payedUsers = this.game.users.filter(user => {
                    return user.pivot.payment_status === 'accepted';
                });

                return payedUsers.length < 2;
            }
        },

        mounted() {
            Nova.request().get('/nova-vendor/bunqpoly/games/' + this.game_id)
                .then(response => {
                    this.game = response.data;
                });

            Echo.private(`game.${this.game_id}`)
                .listen('.game.default.changed', (event) => {
                    this.game = event.game;
                })
                .listen('.game.status.changed', (event) => {
                    this.handelStatusChange(event.game);
                });
        },

        methods: {
            getIcon(status) {
                switch (status) {
                    case "accepted":
                        return "check";
                    case "rejected":
                        return "times";
                    default:
                        return "spinner";
                }
            },

            startGame() {
                Nova.request().post('/nova-vendor/bunqpoly/games/' + this.game_id + '/start')
                    .then(response => {
                        console.log("starting game");
                    });
            },

            handelStatusChange(game) {
                if (game.status === 'playing') {
                    this.$router.push({ name: 'bunqpoly-game', params: { id: game.id }});
                }
            }
        },

    }
</script>

<style>
    /* Scoped Styles */
</style>
