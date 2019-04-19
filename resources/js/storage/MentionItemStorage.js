
export default {
    fetchAvailableItems(resourceName, fieldAttribute, params) {
        return Nova.request().get(
            `/nova-vendor/nova-translatable/${resourceName}/mention-items/${fieldAttribute}`,
            params
        )
    },
}

