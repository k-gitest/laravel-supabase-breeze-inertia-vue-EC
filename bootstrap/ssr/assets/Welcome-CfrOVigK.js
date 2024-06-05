import { defineComponent, unref, withCtx, createTextVNode, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderStyle, ssrInterpolate } from "vue/server-renderer";
import { usePage, Head, Link } from "@inertiajs/vue3";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Welcome",
  __ssrInlineRender: true,
  props: {
    canLogin: { type: Boolean },
    canRegister: { type: Boolean },
    laravelVersion: {},
    phpVersion: {}
  },
  setup(__props) {
    var _a;
    const { props } = usePage();
    const user = (_a = props == null ? void 0 : props.auth) == null ? void 0 : _a.user;
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Welcome" }, null, _parent));
      _push(`<header><div class="navbar bg-base-100"><div class="flex-1"><a class="btn btn-ghost text-xl">daisyUI</a></div>`);
      if (_ctx.canLogin) {
        _push(`<nav class="navbar-end gap-2">`);
        if (unref(user)) {
          _push(ssrRenderComponent(unref(Link), {
            href: _ctx.route("dashboard"),
            class: "btn"
          }, {
            default: withCtx((_, _push2, _parent2, _scopeId) => {
              if (_push2) {
                _push2(` Dashboard `);
              } else {
                return [
                  createTextVNode(" Dashboard ")
                ];
              }
            }),
            _: 1
          }, _parent));
        } else {
          _push(`<!--[-->`);
          _push(ssrRenderComponent(unref(Link), {
            href: _ctx.route("login"),
            class: "btn"
          }, {
            default: withCtx((_, _push2, _parent2, _scopeId) => {
              if (_push2) {
                _push2(` Log in `);
              } else {
                return [
                  createTextVNode(" Log in ")
                ];
              }
            }),
            _: 1
          }, _parent));
          if (_ctx.canRegister) {
            _push(ssrRenderComponent(unref(Link), {
              href: _ctx.route("register"),
              class: "btn"
            }, {
              default: withCtx((_, _push2, _parent2, _scopeId) => {
                if (_push2) {
                  _push2(` Register `);
                } else {
                  return [
                    createTextVNode(" Register ")
                  ];
                }
              }),
              _: 1
            }, _parent));
          } else {
            _push(`<!---->`);
          }
          _push(ssrRenderComponent(unref(Link), {
            href: _ctx.route("admin.login"),
            class: "btn"
          }, {
            default: withCtx((_, _push2, _parent2, _scopeId) => {
              if (_push2) {
                _push2(` admin/Log in `);
              } else {
                return [
                  createTextVNode(" admin/Log in ")
                ];
              }
            }),
            _: 1
          }, _parent));
          if (_ctx.canRegister) {
            _push(ssrRenderComponent(unref(Link), {
              href: _ctx.route("admin.register"),
              class: "btn"
            }, {
              default: withCtx((_, _push2, _parent2, _scopeId) => {
                if (_push2) {
                  _push2(` admin/Register `);
                } else {
                  return [
                    createTextVNode(" admin/Register ")
                  ];
                }
              }),
              _: 1
            }, _parent));
          } else {
            _push(`<!---->`);
          }
          _push(ssrRenderComponent(unref(Link), {
            href: _ctx.route("todo.index"),
            class: "btn"
          }, {
            default: withCtx((_, _push2, _parent2, _scopeId) => {
              if (_push2) {
                _push2(` Todo `);
              } else {
                return [
                  createTextVNode(" Todo ")
                ];
              }
            }),
            _: 1
          }, _parent));
          _push(ssrRenderComponent(unref(Link), {
            href: _ctx.route("product.index"),
            class: "btn"
          }, {
            default: withCtx((_, _push2, _parent2, _scopeId) => {
              if (_push2) {
                _push2(` EC/user `);
              } else {
                return [
                  createTextVNode(" EC/user ")
                ];
              }
            }),
            _: 1
          }, _parent));
          _push(ssrRenderComponent(unref(Link), {
            href: _ctx.route("admin.product.index"),
            class: "btn"
          }, {
            default: withCtx((_, _push2, _parent2, _scopeId) => {
              if (_push2) {
                _push2(` EC/admin `);
              } else {
                return [
                  createTextVNode(" EC/admin ")
                ];
              }
            }),
            _: 1
          }, _parent));
          _push(ssrRenderComponent(unref(Link), {
            href: _ctx.route("contact.create"),
            class: "btn"
          }, {
            default: withCtx((_, _push2, _parent2, _scopeId) => {
              if (_push2) {
                _push2(` contact `);
              } else {
                return [
                  createTextVNode(" contact ")
                ];
              }
            }),
            _: 1
          }, _parent));
          _push(`<!--]-->`);
        }
        _push(`</nav>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></header><main class="mt-6"><div class="hero min-h-screen bg-base-200"><div class="hero-content flex-col lg:flex-row"><img src="https://img.daisyui.com/images/stock/photo-1635805737707-575885ab0820.jpg" class="max-w-sm rounded-lg shadow-2xl"><div><h1 class="text-5xl font-bold">Box Office News!</h1><p class="py-6">Provident cupiditate voluptatem et in. Quaerat fugiat ut assumenda excepturi exercitationem quasi. In deleniti eaque aut repudiandae et a id nisi.</p><button class="btn btn-primary">Get Started</button></div></div></div><div class="carousel rounded-box"><div class="carousel-item"><img src="https://img.daisyui.com/images/stock/photo-1559703248-dcaaec9fab78.jpg" alt="Burger"></div><div class="carousel-item"><img src="https://img.daisyui.com/images/stock/photo-1565098772267-60af42b81ef2.jpg" alt="Burger"></div><div class="carousel-item"><img src="https://img.daisyui.com/images/stock/photo-1572635148818-ef6fd45eb394.jpg" alt="Burger"></div><div class="carousel-item"><img src="https://img.daisyui.com/images/stock/photo-1494253109108-2e30c049369b.jpg" alt="Burger"></div><div class="carousel-item"><img src="https://img.daisyui.com/images/stock/photo-1550258987-190a2d41a8ba.jpg" alt="Burger"></div><div class="carousel-item"><img src="https://img.daisyui.com/images/stock/photo-1559181567-c3190ca9959b.jpg" alt="Burger"></div><div class="carousel-item"><img src="https://img.daisyui.com/images/stock/photo-1601004890684-d8cbf643f5f2.jpg" alt="Burger"></div></div><div class="hero min-h-screen" style="${ssrRenderStyle({ "background-image": "url(https://img.daisyui.com/images/stock/photo-1507358522600-9f71e620c44e.jpg)" })}"><div class="hero-overlay bg-opacity-60"></div><div class="hero-content text-center text-neutral-content"><div class="max-w-md"><h1 class="mb-5 text-5xl font-bold">Hello there</h1><p class="mb-5">Provident cupiditate voluptatem et in. Quaerat fugiat ut assumenda excepturi exercitationem quasi. In deleniti eaque aut repudiandae et a id nisi.</p><button class="btn btn-primary">Get Started</button></div></div></div></main><footer class="footer p-10 bg-neutral text-neutral-content"><aside> ロゴ <p>Laravel v${ssrInterpolate(_ctx.laravelVersion)} (PHP v${ssrInterpolate(_ctx.phpVersion)})</p></aside><nav><h6 class="footer-title">Social</h6><div class="grid grid-flow-col gap-4"><a>アイコン</a><a>アイコン</a><a>アイコン</a></div></nav></footer><!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Welcome.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
