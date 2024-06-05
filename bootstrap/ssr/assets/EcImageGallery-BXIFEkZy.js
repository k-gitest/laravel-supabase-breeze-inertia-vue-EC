import { defineComponent, ref, mergeProps, unref, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderList, ssrRenderAttr } from "vue/server-renderer";
import { s as supabaseURL, a as supabaseNoImage } from "./supabase-B-jbb2wE.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "EcImageGallery",
  __ssrInlineRender: true,
  props: {
    images: {}
  },
  setup(__props) {
    const props = __props;
    const selectedImage = ref();
    if (props.images && props.images.length) {
      selectedImage.value = supabaseURL + props.images[0].path;
    } else {
      selectedImage.value = supabaseURL + supabaseNoImage;
    }
    return (_ctx, _push, _parent, _attrs) => {
      if (_ctx.images && _ctx.images.length) {
        _push(`<div${ssrRenderAttrs(mergeProps({ class: "flex gap-2" }, _attrs))}><div class="flex flex-col gap-2"><!--[-->`);
        ssrRenderList(_ctx.images, (image) => {
          _push(`<div class="size-10 border overflow-hidden cursor-pointer"><img${ssrRenderAttr("src", unref(supabaseURL) + image.path)} class="object-cover"></div>`);
        });
        _push(`<!--]--></div><div class="w-96 h-lg border flex justify-center items-center overflow-hidden"><img${ssrRenderAttr("src", selectedImage.value)} class="object-cover w-full"></div></div>`);
      } else {
        _push(`<div${ssrRenderAttrs(mergeProps({ class: "max-w-48" }, _attrs))}><img${ssrRenderAttr("src", unref(supabaseURL) + unref(supabaseNoImage))} class="rounded-lg"></div>`);
      }
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/EcImageGallery.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
