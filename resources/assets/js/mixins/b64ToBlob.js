export const b64toBlob = {
  methods: {
    /**
     * @description:convert base64 to image
     * @param {string} b64Data
     * @param {string} contentType
     * @param {number} sliceSize
     *
     * @return {object} blob
     */
    b64toBlob(fileName, sliceSize) {
      let cropImg = fileName.dataURL;
      let block = cropImg.split(";");
      let contentType = block[0].split(":")[1];
      let realData = block[1].split(",")[1];
      contentType = contentType || "";
      sliceSize = sliceSize || 512;

      var byteCharacters = atob(realData);
      var byteArrays = [];

      for (
        var offset = 0;
        offset < byteCharacters.length;
        offset += sliceSize
      ) {
        var slice = byteCharacters.slice(offset, offset + sliceSize);

        var byteNumbers = new Array(slice.length);
        for (var i = 0; i < slice.length; i++) {
          byteNumbers[i] = slice.charCodeAt(i);
        }

        var byteArray = new Uint8Array(byteNumbers);

        byteArrays.push(byteArray);
      }

      var blob = new Blob(byteArrays, { type: contentType });
      return blob;
    },

    /**
     * @description:convert b64
     * @param {string} b64Data
     * @param {string} fileName
     *
     */
    convertBase64ToBlob(b64Data, fileName = "file") {
      try {
        let arr = b64Data.split(",");
        let mime = arr[0].match(/:(.*?);/)[1];
        let bstr = atob(arr[1]);
        let n = bstr.length;
        let u8arr = new Uint8Array(n);

        while (n--) {
          u8arr[n] = bstr.charCodeAt(n);
        }

        return new File([u8arr], fileName, { type: mime });
      } catch (error) {
        return null;
      }
    },
  },
};
