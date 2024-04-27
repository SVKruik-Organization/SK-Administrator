<script lang="ts">
import type { DateFormat, OperatorUser, PaginationValues, tableColumn } from '@/assets/customTypes';
import { testMembers } from '@/assets/dev-data/dev-members';
import { getDate } from '@/utils/date';
import { defineComponent } from 'vue';
import type { RouteLocation } from 'vue-router';

export default defineComponent({
    name: "AccountsMembersTab",
    data() {
        return {
            "members": testMembers as Array<OperatorUser>,
            "rowLimit": 15 as number,
            "pageCount": 1 as number,
            "selectedMember": null as unknown as OperatorUser,
            "activeFilter": "id" as string,
            "filterOrder": "asc" as string,
            "tableColumns": [{
                "title": "ID",
                "comparator": "memberId",
                "id": 1
            },
                {
                    "title": "Snowflake",
                    "comparator": "snowflake",
                    "id": 2
                },
                {
                    "title": "Username",
                    "comparator": "username",
                    "id": 3
                },
                {
                    "title": "Email",
                    "comparator": "email",
                    "id": 4
                },
                {
                    "title": "Service Tag",
                    "comparator": "service_tag",
                    "id": 5
                },
                {
                    "title": "Creation Date",
                    "comparator": "date_creation",
                    "id": 6
                },
                {
                    "title": "Update Date",
                    "comparator": "date_update",
                    "id": 7
                },
                {
                    "title": "Status",
                    "comparator": "status",
                    "id": 8
                }] as Array<tableColumn>
        }
    },
    watch: {
        $route(to: RouteLocation) {
            if (to.path === "/panel/operations/accounts/members")
                (this.$refs["memberCheckAllInput"] as HTMLInputElement).checked = false;
        }
    },
    mounted() {
        // Page
        this.pageCount = Math.ceil(this.members.length / this.rowLimit);
        if (this.$route.query.page) {
            const currentPage: number = parseInt(this.$route.query.page.toString());
            if (currentPage > this.pageCount) this.$router.push(`/panel/operations/accounts/members?page=${this.pageCount}${this.getMemberUrl(undefined)}`);
        } else this.$router.push(`/panel/operations/accounts/members?page=1${this.getMemberUrl(undefined)}`);

        // Member
        const currentMember: number | null = this.getCurrentMember();
        if (currentMember) this.selectedMember = this.findMemberById(currentMember);
    },
    methods: {
        dateFormat(input: Date | undefined): DateFormat {
            return getDate(input);
        },
        selectRow(id: number, event: MouseEvent): void {
            const eventTarget: HTMLInputElement = event.target as HTMLInputElement;
            if (eventTarget.type === "checkbox") return;
            this.selectedMember = this.findMemberById(id);
            this.$router.push(`/panel/operations/accounts/members?page=${this.getCurrentPage()}${this.getMemberUrl(id)}`);
        },
        navigate(targetPage: number): void {
            if (this.getCurrentPage() === targetPage) return;
            this.$router.push(`/panel/operations/accounts/members?page=${targetPage}${this.getMemberUrl(undefined)}`);
        },
        navigateNext(): void {
            this.$router.push(`/panel/operations/accounts/members?page=${this.getCurrentPage() + 1}${this.getMemberUrl(undefined)}`);
        },
        navigatePrevious(): void {
            this.$router.push(`/panel/operations/accounts/members?page=${this.getCurrentPage() + -1}${this.getMemberUrl(undefined)}`);
        },
        getActiveClass(index: number): string {
            if (index === parseInt(this.$route.query.page?.toString() || "")) {
                return "active-pagination-button";
            } else return "";
        },
        getPagination(): PaginationValues {
            let rawCurrentPage = this.getCurrentPage();
            if (rawCurrentPage > this.pageCount) rawCurrentPage = this.pageCount;
            const rawLowerBound = this.rowLimit * rawCurrentPage - this.rowLimit;
            const lowerBound = (rawLowerBound < 0 ? 0 : rawLowerBound) || 0;
            const upperBound = (this.rowLimit * rawCurrentPage) || this.rowLimit;
            return { lowerBound, upperBound };
        },
        getMemberUrl(id: number | undefined): string {
            if (id) return `&member=${id}`;
            const currentMember = this.getCurrentMember();
            if (currentMember) {
                return `&member=${currentMember}`;
            } else return "";
        },
        getCurrentPage(): number {
            if (!this.$route.query.page) return 1;
            return parseInt(this.$route.query.page.toString());
        },
        getCurrentMember(): number | null {
            if (!this.$route.query.member) return null;
            return parseInt(this.$route.query.member.toString());
        },
        findMemberById(id: number): OperatorUser {
            return this.members.filter((member: OperatorUser) => member.memberId === id)[0];
        },
        numberStrip(input: number, max: number): number {
            return input > max ? max : input;
        },
        getNextPaginationButton(): boolean {
            return this.getCurrentPage() < this.pageCount;
        },
        getPreviousPaginationButton(): boolean {
            return this.getCurrentPage() > 1
        },
        memberCheck(id: number, event: Event): void {
            const eventTarget = event.target as HTMLInputElement;
            console.log(eventTarget.checked, id);
        },
        memberCheckAll(event: Event): void {
            const eventTarget = event.target as HTMLInputElement;
            const pagination: PaginationValues = this.getPagination();
            const checkedMembers: Array<OperatorUser> = this.members.slice(pagination.lowerBound, pagination.upperBound);
            console.log(eventTarget.checked, checkedMembers);
        },
        filterBy(comparator: string): void {
            if (comparator === this.activeFilter) this.filterOrder === "asc" ? this.filterOrder = "desc" : this.filterOrder = "asc";
            this.activeFilter = comparator;

            const numberComparators: Array<string> = ["memberId", "status"];
            const dateComparators: Array<string> = ["date_creation", "date_update"];

            // Number & Boolean
            if (numberComparators.includes(comparator)) {
                this.members.sort((a, b) => {
                    return this.filterOrder === "desc"
                        ? (b[comparator] as number) - (a[comparator] as number)
                        : (a[comparator] as number) - (b[comparator] as number);
                });

                // Date
            } else if (dateComparators.includes(comparator)) {
                this.members.sort((a, b) => {
                    const dateA = new Date(a[comparator] as Date).getTime();
                    const dateB = new Date(b[comparator] as Date).getTime();
                    return this.filterOrder === "desc"
                        ? dateB - dateA
                        : dateA - dateB;
                });

                // String
            } else {
                this.members.sort((a, b) => {
                    const propA = (a[comparator] as string).toLowerCase();
                    const propB = (b[comparator] as string).toLowerCase();
                    return this.filterOrder === "desc"
                        ? propB.localeCompare(propA)
                        : propA.localeCompare(propB);
                });
            }
        }
    }
});
</script>

<template>
    <div class="content-parent flex-col">
        <section class="table-wrapper flex-col">
            <table>
                <thead>
                <tr class="table-header-container">
                    <th class="top-left-header"><input ref="memberCheckAllInput" type="checkbox"
                                                       @change="memberCheckAll($event)"></th>
                    <th v-for="tableColumn in tableColumns" :key="tableColumn.id"
                        @click="filterBy(tableColumn.comparator)">
                        <div class="table-header-item">
                            <p>{{ tableColumn.title }}</p>
                            <i :class="`fa-regular fa-square-caret-${filterOrder === 'asc' ? 'down' : 'up'}`"></i>
                        </div>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="member in members.slice(getPagination().lowerBound, getPagination().upperBound)"
                    :id="member.memberId.toString()" :key="member.memberId"
                    :class="selectedMember && selectedMember.memberId === member.memberId ? 'active-pagination-button' : ''"
                    class="table-data-row" @click="selectRow(member.memberId, $event)">
                    <td class="left-data"><input type="checkbox" @change="memberCheck(member.memberId, $event)">
                    </td>
                    <td>{{ member.memberId }}</td>
                    <td>{{ member.snowflake }}</td>
                    <td>{{ member.username }}</td>
                    <td>{{ member.email }}</td>
                    <td>{{ member.service_tag }}</td>
                    <td>{{ dateFormat(member.date_creation).date }}</td>
                    <td>{{ dateFormat(member.date_creation).date }}</td>
                    <td class="right-data">
                        <div class="status-indicator">
                                <span :style="`background-color: var(--color-${member.status ? 'success' : 'warning'})`"
                                      class="status-indicator-color"></span>
                            <p>{{ member.status ? "Enabled" : "Disabled" }}</p>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
            <section class="table-footer">
                <p>Showing: {{ getPagination().lowerBound + 1 }} -
                    {{ numberStrip(getPagination().upperBound, members.length) }} of
                    {{ members.length }}
                </p>
                <div class="pagination-container">
                    <button v-if="getPreviousPaginationButton()" class="pagination-button previous-pagination-button" type="button"
                            @click="navigatePrevious()">
                        <i class="fa-regular fa-angle-left"></i>
                    </button>
                    <button v-for="index in pageCount" :key="index" :class="`pagination-button ${getActiveClass(index)}`"
                            type="button" @click="navigate(index)">
                        {{ index }}
                    </button>
                    <button v-if="getNextPaginationButton()" class="pagination-button next-pagination-button" type="button"
                            @click="navigateNext()">
                        <i class="fa-regular fa-angle-right"></i>
                    </button>
                </div>
            </section>
        </section>
        <section v-if="selectedMember" class="selected-container flex-col">
            <strong>Selected Member Details:</strong>
            <span class="splitter"></span>
            <div class="selected-details-parent">
                <div class="selected-details flex">
                    <section class="left-details flex-col">
                        <p class="bold splitter-vertical">Operator Information:</p>
                        <ul class="flex-col">
                            <li>ID</li>
                            <li>Username</li>
                            <li>Email</li>
                            <li>Service Tag</li>
                            <li>Date Creation</li>
                            <li>Date Update</li>
                        </ul>
                        <p class="bold splitter-vertical">Discord Information:</p>
                        <ul class="flex-col">
                            <li>Snowflake</li>
                            <li>Username</li>
                            <li>Pincode</li>
                            <li>Date Creation</li>
                            <li>Date Update</li>
                            <li>Daily Expiry</li>
                        </ul>
                    </section>
                    <section class="right-details flex-col">
                        <span class="splitter-vertical"></span>
                        <ul class="flex-col">
                            <li>{{ selectedMember.memberId }}</li>
                            <li>{{ selectedMember.username }}</li>
                            <li>{{ selectedMember.email }}</li>
                            <li>{{ selectedMember.service_tag }}</li>
                            <li>{{ dateFormat(selectedMember.date_creation).date }}</li>
                            <li>{{ dateFormat(selectedMember.date_update).date }}</li>
                        </ul>
                        <span class="splitter-vertical"></span>
                        <ul class="flex-col">
                            <li>{{ selectedMember.snowflake }}</li>
                            <li>complex9078</li>
                            <li>9078</li>
                            <li>{{ dateFormat(undefined).date }}</li>
                            <li>{{ dateFormat(undefined).date }}</li>
                            <li>{{ dateFormat(undefined).date }}</li>
                        </ul>
                    </section>
                </div>
                <div>
                    <p>Part Of:</p>
                </div>
            </div>
        </section>
    </div>
</template>

<style scoped>
.content-parent {
    gap: 10px;
}

.table-wrapper {
    gap: 0;
}

.status-indicator {
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.status-indicator-color {
    opacity: 0.5;
    width: 90%;
    height: 30px;
    border-radius: var(--border-radius-high);
    position: absolute;
}

.top-left-header {
    text-align: center;
}

.selected-container {
    gap: 10px;
    border-radius: var(--border-radius-mid);
    border: 2px solid var(--color-border);
    min-height: 300px;
    height: fit-content;
    box-sizing: border-box;
    padding: 10px;
}

.selected-details-parent {
    display: flex;
    width: 100%;
    gap: 10px;
    height: 100%;
}

.selected-details-parent > * {
    width: 50%;
    height: 100%;
}

.selected-details {
    gap: 50px;
}

.left-details,
.right-details {
    gap: 10px;
}

.right-details {
    text-align: right;
}

.splitter-vertical {
    height: 20px;
}

ul:first-of-type {
    margin-bottom: 10px;
}
</style>
