<script lang="ts" setup>
import { useFetchValidateToken } from "@/utils/fetch/auth/useFetchValidateToken";

const query = useRoute().query;
const token = query.token as string | undefined;
if (!token) navigateTo("/login");

await useFetchValidateToken(token!);

navigateTo("/panel");
</script>