<script lang="ts" setup>
import UserLayout from '@/layouts/UserLayout.vue';
import TabBar from '@/components/ui/TabBar.vue';
import TabItem from '@/components/ui/TabItem.vue';
import Profile from '@/components/tabs/preferences/Profile.vue';
import Notifications from '@/components/tabs/preferences/Notifications.vue';
import Security from '@/components/tabs/preferences/Security.vue';

defineOptions({
    layout: UserLayout,
});
</script>

<template>
    <TabBar>
        <TabItem name="Profile">
            <Profile />
        </TabItem>
        <TabItem name="Notifications">
            <Notifications />
        </TabItem>
        <TabItem name="Security">
            <Security />
        </TabItem>
    </TabBar>
</template>