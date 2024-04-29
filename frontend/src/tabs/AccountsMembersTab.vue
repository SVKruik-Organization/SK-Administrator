<script lang="ts">
import {
    type BooleanTableData,
    type DateFormat,
    type OperatorUser,
    PromptTypes,
    type TableColumn
} from '@/assets/customTypes';
import { testMembers } from '@/assets/dev-data/dev-members';
import TableComponent from '@/components/TableComponent.vue';
import { getDate } from '@/utils/date';
import { defineComponent } from 'vue';

export default defineComponent({
    name: "AccountsMembersTab",
    data() {
        return {
            "members": testMembers as Array<OperatorUser>,
            "rowLimit": 15 as number,
            "pageCount": 1 as number,
            "selectedMember": null as unknown as OperatorUser,
            "activeSortName": "ID" as string,
            "filterOrder": "asc" as string,
            "activeSearch": "" as string,
            "baseRoute": "/panel/operations/accounts/members" as string,
            "tableBooleanData": {
                "trueColor": PromptTypes.success,
                "falseColor": PromptTypes.warning,
                "trueMessage": "Enabled",
                "falseMessage": "Disabled"
            } as BooleanTableData,
            "tableColumns": [{
                "title": "ID",
                "comparator": "id",
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
            }] as Array<TableColumn>,
            "tableComparatorsNumber": ["id", "status"],
            "tableComparatorsDate": ["date_creation", "date_update"]
        }
    },
    components: {
        TableComponent
    },
    watch: {
        activeSearch(to: string, from: string) {
            // TODO: #22
        }
    },
    mounted() {
        // Page
        this.pageCount = Math.ceil(this.members.length / this.rowLimit);
        if (this.$route.query.page) {
            const currentPage: number = parseInt(this.$route.query.page.toString());
            if (currentPage > this.pageCount) this.$router.push(`${this.baseRoute}?page=${this.pageCount}${this.getMemberUrl(undefined)}`);
        } else this.$router.push(`${this.baseRoute}?page=1${this.getMemberUrl(undefined)}`);

        // Member
        const currentMember: number | null = this.getCurrentMember();
        if (currentMember) this.selectedMember = this.findMemberById(currentMember);
    },
    methods: {
        dateFormat(input: Date | undefined): DateFormat {
            return getDate(input);
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
            return this.members.filter((member: OperatorUser) => member.id === id)[0];
        },
        searchValidation(event: KeyboardEvent): void {
            const input = this.$refs["searchBar"] as HTMLInputElement;
            let inputValue = input.value;
            if (event.code !== "Enter" || inputValue.length === 0) return;
            this.activeSearch = inputValue;
        },
        selectMember(currentPage: number, id: number): void {
            this.$router.push(`${this.baseRoute}?page=${currentPage}&member=${id}`);
            this.selectedMember = this.findMemberById(id);
        },
        singleSelectMember(memberId: number, event: Event): void {
            // TODO: #23
            console.log(memberId, event);
        },
        bulkSelectMember(event: Event): void {
            // TODO: #23
            console.log(event);
        },
        filterProperties(sortName: string, sortOrder: string): void {
            this.activeSortName = sortName;
            this.filterOrder = sortOrder;
        }
    }
});
</script>

<template>
    <div class="content-parent flex-col">
        <section class="filter-container flex">
            <div class="filter-left flex pill">
                <i class="fa-regular fa-magnifying-glass pointer"
                    @click="($refs['searchBar'] as HTMLInputElement).focus()"></i>
                <input ref="searchBar" placeholder="Search" type="text" @keyup="searchValidation($event)">
            </div>
            <div class="filter-right flex">
                <div class="pill no-select">
                    <p>Sort By: {{ activeSortName }}</p>
                </div>
                <div class="pill no-select">
                    <p>Order: {{ filterOrder === "asc" ? "Ascending" : "Descending" }}</p>
                </div>
            </div>
        </section>
        <TableComponent :base-route="baseRoute" :boolean-table-data="tableBooleanData" :items="members"
            :page-count="pageCount" :row-limit="rowLimit" :table-columns="tableColumns"
            :table-comparators-date="tableComparatorsDate" :table-comparators-number="tableComparatorsNumber"
            :url-query="getMemberUrl(undefined)" :url-query-name="'member'" @select-row="selectMember"
            @select-single="singleSelectMember" @select-bulk="bulkSelectMember"></TableComponent>
        <section v-if="selectedMember" class="flex-col">
            <div class="selected-member-card flex-col">
                <div class="selected-member-card-header">
                    <p>Selected Member Details</p>
                </div>
                <div class="selected-member-card-content flex">
                    <section class="selected-member-keys flex-col">
                        <p class="bold splitter-vertical">Operator Account Information:</p>
                        <ul class="flex-col">
                            <li>ID</li>
                            <li>Username</li>
                            <li>Email</li>
                            <li>Service Tag</li>
                            <li>Date Creation</li>
                            <li>Date Update</li>
                        </ul>
                        <p class="bold splitter-vertical">General Account Information:</p>
                        <ul class="flex-col">
                            <li>Snowflake</li>
                            <li>Username</li>
                            <li>Pincode</li>
                            <li>Date Creation</li>
                            <li>Date Update</li>
                            <li>Daily Expiry</li>
                        </ul>
                    </section>
                    <section class="selected-member-values flex-col">
                        <span class="splitter-vertical"></span>
                        <ul class="flex-col">
                            <li>{{ selectedMember.id }}</li>
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
            </div>
        </section>
    </div>
</template>

<style scoped>
.content-parent {
    gap: 20px;
}

/* Filters */
.filter-container {
    justify-content: space-between;
    gap: 0;
}

.filter-left {
    border: 1px solid var(--color-border);
    width: 250px;
    gap: 15px;
}

/* Table & Content */
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

/* Selected Member */
.selected-member-card {
    gap: 10px;
    border-radius: var(--border-radius-mid);
    border: 2px solid var(--color-fill);
    width: fit-content;
    height: fit-content;
}

.selected-member-card-header {
    background-color: var(--color-fill);
    border-top-left-radius: 7px;
    border-top-right-radius: 7px;
    box-sizing: border-box;
    padding: 10px;
}

.selected-member-card-content {
    box-sizing: border-box;
    padding: 10px;
}

.selected-member-keys,
.selected-member-values {
    gap: 10px;
}

.selected-member-values {
    text-align: right;
}

.splitter-vertical {
    height: 20px;
}

ul:first-of-type {
    margin-bottom: 10px;
}
</style>
