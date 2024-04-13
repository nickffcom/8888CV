<template>
  <div>
    <router-view />
  </div>
</template>

<script>
import {
  getDetailMeetingByLinkID,
  updateMeeting,
} from "../apis/meeting/meeting";

export default {
  name: "App",
  watch: {
    "$route.name": {
      handler: function (newVal) {
        if (newVal == "editPublic") {
          const params = { link_id: this.$route.params.link_id };
          this.emitter.emit("isShowLoading", true);
          getDetailMeetingByLinkID(params)
            .then((res) => {
              const userInfo = JSON.parse(localStorage.getItem("USER_INFO"));
              let formData = new FormData();
              formData.append(`emails[${0}]`, userInfo.email);
              if (!res.data.current_participant_id) {
                updateMeeting(res.data.id, formData)
                  .then((res) => {
                    this.$router.push({
                      name: "EditMeeting",
                      params: { id: res.data.id },
                    });
                    this.emitter.emit("isShowLoading", false);
                  })
                  .catch((err) => {
                    console.log("there was an error", err);
                    this.emitter.emit("isShowLoading", false);
                  });
              } else {
                this.$router.push({
                  name: "EditMeeting",
                  params: { id: res.data.id },
                });
              }
            })
            .catch((err) => {
              console.log("there was an error", err);
              this.emitter.emit("isShowLoading", false);
            });
        }
      },
      deep: true,
      immediate: true,
    },
  },
};
</script>
