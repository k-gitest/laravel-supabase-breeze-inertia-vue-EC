import { defineComponent, unref, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderList, ssrRenderComponent, ssrRenderAttr, ssrInterpolate, ssrIncludeBooleanAttr, ssrLooseContain } from "vue/server-renderer";
import { Link, useForm } from "@inertiajs/vue3";
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "Pagination",
  __ssrInlineRender: true,
  props: {
    links: {}
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      if (_ctx.links && _ctx.links.length) {
        _push(`<div${ssrRenderAttrs(_attrs)}><div class="join flex justify-center p-5"><!--[-->`);
        ssrRenderList(_ctx.links, (link) => {
          _push(`<!--[-->`);
          if (link.url) {
            _push(ssrRenderComponent(unref(Link), {
              class: ["join-item btn btn-sm", { "bg-neutral text-white hover:text-white hover:bg-neutral": link.active }],
              href: link.url
            }, null, _parent));
          } else {
            _push(`<div class="join-item btn btn-sm text-slate-400 hover:text-slate-400">${link.label}</div>`);
          }
          _push(`<!--]-->`);
        });
        _push(`<!--]--></div></div>`);
      } else {
        _push(`<!---->`);
      }
    };
  }
});
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Pagination.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "SearchBox",
  __ssrInlineRender: true,
  props: {
    categories: {},
    filters: {}
  },
  emits: ["searchSubmit"],
  setup(__props, { emit: __emit }) {
    var _a, _b;
    const props = __props;
    const form = useForm({
      q: ((_a = props.filters) == null ? void 0 : _a.q) || "",
      category_ids: ((_b = props.filters) == null ? void 0 : _b.category_ids) || []
    });
    return (_ctx, _push, _parent, _attrs) => {
      var _a2;
      _push(`<form${ssrRenderAttrs(_attrs)}><div><input type="text"${ssrRenderAttr("value", unref(form).q)}></div><div><!--[-->`);
      ssrRenderList((_a2 = _ctx.categories) == null ? void 0 : _a2.data, (category) => {
        _push(`<label class="label cursor-pointer"><span class="label-text">${ssrInterpolate(category.name)}</span><input type="checkbox"${ssrIncludeBooleanAttr(Array.isArray(unref(form).category_ids) ? ssrLooseContain(unref(form).category_ids, category.id) : unref(form).category_ids) ? " checked" : ""}${ssrRenderAttr("value", category.id)} class="checkbox checkbox-sm"></label>`);
      });
      _push(`<!--]--></div><button type="submit" class="btn btn-sm">送信</button></form>`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/SearchBox.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _,
  _sfc_main$1 as a
};
