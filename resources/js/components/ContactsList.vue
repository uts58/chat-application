<template>
    <div class="contacts-list">
        <ul>
            <li v-for="contact in sortedContacts" :key="contact.id" @click="selectContact(contact)"
                :class="{'selected': contact === selected}">
                <div class="avatar">
                    <img :src="contact.profile_image" :alt="contact.name">
                </div>
                <div class="contact">
                    <p class="name">{{contact.name}}</p>
                    <p class="email">{{contact.email}}</p>
                </div>
                <span class="unread" v-if="contact.unread">{{contact.unread}}</span>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                selected: this.contacts.length ? this.contacts[0] : null
            };
        },
        props: {
            contacts: {
                type: Array,
                default: []
            }
        },
        methods: {
            selectContact(contact) {
                this.selected = contact;
                this.$emit('selected', contact);
            }
        },
        computed: {
            sortedContacts() {
                return _.sortBy(this.contacts, [(contact) => {
                    if (contact === this.selected) {
                        return Infinity;
                    }
                    return contact.unread;
                }]).reverse();
            }
        }
    }
</script>
<style lang="scss" scoped>
    .contacts-list {
        flex: 2;
        max-height: 600px;
        overflow-y: scroll;
        border-left: 1px solid lightslategray;

        ul {
            list-style-type: none;
            padding-left: 0;

            li {
                display: flex;
                padding: 2px;
                border-bottom: 1px solid lightslategray;
                height: 80px;
                position: relative;
                cursor: pointer;

                &.selected {
                    background: lightgray;
                }

                span.unread {
                    background: green;
                    color: white;
                    right: 11px;
                    top: 20px;
                    position: absolute;
                    display: flex;
                    font-weight: 700;
                    min-width: 20px;
                    justify-content: center;
                    align-items: center;
                    line-height: 20px;
                    font-size: 12px;
                    padding: 0 4px;
                    border-radius: 50%;
                }
            }

            .avatar {
                display: flex;
                flex: 1;
                align-items: center;

                img {
                    width: 35px;
                    border-radius: 50%;
                    margin: 0 auto;
                }
            }

            .contact {
                flex: 3;
                font-size: 12px;
                overflow: hidden;
                display: flex;
                flex-direction: column;
                justify-content: center;

                p {
                    margin: 0;

                    &.name {
                        font-weight: bold;
                    }
                }
            }
        }
    }
</style>
