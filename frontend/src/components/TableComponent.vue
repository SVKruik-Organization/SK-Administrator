<script lang="ts">
import { type BooleanTableData, type DateFormat, type PaginationValues, type TableColumn } from '@/assets/customTypes';
import { getDate } from '@/utils/date';
import type { PropType } from 'vue';
import { defineComponent } from 'vue';
import type { RouteLocation } from 'vue-router';

export default defineComponent({
    name: "TableComponent",
    props: {
        "items": { type: Array<any>, required: true },
        "pageCount": { type: Number, required: true },
        "rowLimit": { type: Number, required: true },
        "tableColumns": { type: Array<TableColumn>, required: true },
        "baseRoute": { type: String, required: true },
        "urlQuery": { type: String, required: true },
        "urlQueryName": { type: String, required: true },
        "tableComparatorsNumber": { type: Array<string>, required: true },
        "tableComparatorsDate": { type: Array<string>, required: true },
        "booleanTableData": { type: Object as PropType<BooleanTableData>, required: true }
    },
    emits: [
        "selectRow",
        "selectSingle",
        "selectBulk",
        "filterProperties"
    ],
    data() {
        return {
            "activeSort": "id" as string,
            "activeSortName": "ID" as string,
            "filterOrder": "asc" as string,
            "selectedItem": null as any
        }
    },
    watch: {
        $route(to: RouteLocation) {
            if (to.path === this.baseRoute)
                (this.$refs["bulkSelectInput"] as HTMLInputElement).checked = false;
        },
        activeSearch(to: string, from: string) {
            // TODO: #22
        }
    },
    methods: {
        selectRow(id: number, event: MouseEvent): void {
            const eventTarget: HTMLInputElement = event.target as HTMLInputElement;
            if (eventTarget.type === "checkbox") return;
            this.selectedItem = this.findItemById(id);
            this.$emit("selectRow", this.getCurrentPage(), id);
        },
        navigate(targetPage: number): void {
            if (this.getCurrentPage() === targetPage) return;
            this.$router.push(`${this.baseRoute}?page=${targetPage}${this.urlQuery}`);
        },
        navigateNext(): void {
            this.$router.push(`${this.baseRoute}?page=${this.getCurrentPage() + 1}${this.urlQuery}`);
        },
        navigatePrevious(): void {
            this.$router.push(`${this.baseRoute}?page=${this.getCurrentPage() - 1}${this.urlQuery}`);
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
        getCurrentPage(): number {
            if (!this.$route.query.page) return 1;
            return parseInt(this.$route.query.page.toString());
        },
        selectSingle(id: number, event: Event) {
            this.$emit("selectSingle", id, event);
        },
        selectBulk(event: Event) {
            this.$emit("selectBulk", event);
        },
        filterBy(comparator: string, comparatorName: string): void {
            if (comparator === this.activeSort) this.filterOrder === "asc" ? this.filterOrder = "desc" : this.filterOrder = "asc";
            this.activeSort = comparator;
            this.activeSortName = comparatorName;
            this.$emit("filterProperties", this.activeSortName, this.filterOrder);

            const numberComparators: Array<string> = this.tableComparatorsNumber;
            const dateComparators: Array<string> = this.tableComparatorsDate;

            // Number & Boolean
            if (numberComparators.includes(comparator)) {
                this.items.sort((a, b) => {
                    return this.filterOrder === "desc"
                        ? (b[comparator] as number) - (a[comparator] as number)
                        : (a[comparator] as number) - (b[comparator] as number);
                });

                // Date
            } else if (dateComparators.includes(comparator)) {
                this.items.sort((a, b) => {
                    const dateA = new Date(a[comparator] as Date).getTime();
                    const dateB = new Date(b[comparator] as Date).getTime();
                    return this.filterOrder === "desc"
                        ? dateB - dateA
                        : dateA - dateB;
                });

                // String
            } else {
                this.items.sort((a, b) => {
                    const propA = (a[comparator] as string).toLowerCase();
                    const propB = (b[comparator] as string).toLowerCase();
                    return this.filterOrder === "desc"
                        ? propB.localeCompare(propA)
                        : propA.localeCompare(propB);
                });
            }
        },
        dateFormat(input: Date | undefined): DateFormat {
            return getDate(input);
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
        findItemById(id: number): Object {
            return this.items.filter((item: any) => item.id === id)[0];
        },
    },
    mounted() {
        // Item
        let selectedItemId = this.$route.query[this.urlQueryName];
        if (selectedItemId) {
            this.selectedItem = this.findItemById(parseInt(selectedItemId.toString()));
            selectedItemId = `&${this.urlQueryName}=${selectedItemId}`;
        } else selectedItemId = "";


        // Page
        if (this.$route.query.page) {
            const currentPage: number = parseInt(this.$route.query.page.toString());
            if (currentPage > this.pageCount) this.$router.push(`${this.baseRoute}?page=${this.pageCount}${selectedItemId}`);
        } else this.$router.push(`${this.baseRoute}?page=1${selectedItemId}`);
    }
});
</script>

<template>
    <section class="table-wrapper flex-col">
        <table>
            <thead>
                <tr class="table-header-container">
                    <th class="top-left-header"><input ref="bulkSelectInput" type="checkbox"
                            @change="selectBulk($event)"></th>
                    <th v-for="tableColumn in tableColumns" :key="tableColumn.id"
                        @click="filterBy(tableColumn.comparator, tableColumn.title)">
                        <div class="table-header-item">
                            <p>{{ tableColumn.title }}</p>
                            <i :class="`fa-regular fa-square-caret-${filterOrder === 'asc' ? 'down' : 'up'}`"></i>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in items.slice(getPagination().lowerBound, getPagination().upperBound)"
                    :id="item.id.toString()" :key="item.id"
                    :class="selectedItem && selectedItem.id === item.id ? 'active-pagination-button' : ''"
                    class="table-data-row" @click="selectRow(item.id, $event)">
                    <td class="left-data"><input type="checkbox" @change="selectSingle(item.id, $event)">
                    </td>
                    <td v-for="(value, key) in item" :key="key">
                        <template v-if="typeof value === 'object'">
                            {{ dateFormat(value).date }}
                        </template>
                        <template v-else-if="typeof value === 'boolean'">
                            <div class="status-indicator">
                                <span
                                    :style="`background-color: var(--color-${value ? booleanTableData.trueColor : booleanTableData.falseColor})`"
                                    class="status-indicator-color"></span>
                                <p>{{ value ? booleanTableData.trueMessage : booleanTableData.falseMessage }}</p>
                            </div>
                        </template>
                        <template v-else>
                            {{ value }}
                        </template>
                    </td>

                </tr>
            </tbody>
        </table>
        <section class="table-footer">
            <p>Showing: {{ getPagination().lowerBound + 1 }} -
                {{ numberStrip(getPagination().upperBound, items.length) }} of
                {{ items.length }}
            </p>
            <div class="pagination-container">
                <button v-if="getPreviousPaginationButton()" class="pagination-button previous-pagination-button"
                    type="button" @click="navigatePrevious()">
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
</template>

<style scoped>
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
</style>