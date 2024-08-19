
FROM node:22-bookworm-slim AS build

RUN unlink /usr/local/bin/yarn && \
    unlink /usr/local/bin/yarnpkg

FROM buildpack-deps:bookworm

ARG USERNAME=vscode
ARG USER_UID=1000
ARG USER_GID=${USER_UID}

ENV LANG=C.UTF-8

RUN groupadd -g ${USER_GID} ${USERNAME} && \
    useradd -u ${USER_UID} -g ${USER_GID} -G adm,sudo -m -s /bin/bash ${USERNAME}

RUN install -o ${USERNAME} -g ${USERNAME} -m 0755 -d /workspace

RUN --mount=type=cache,target=/var/cache/apt,sharing=locked \
    --mount=type=cache,target=/var/lib/apt,sharing=locked \
    export DEBIAN_FRONTEND=noninteractive && \
    apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y --no-install-recommends \
        bash-completion \
        bind9-dnsutils \
        composer \
        less \
        nkf \
        php-cli \
        php-curl \
        publicsuffix \
        sudo \
        vim \
        whiptail \
        zip

COPY --from=build /usr/local/ /usr/local/

RUN echo "${USERNAME} ALL=(ALL) NOPASSWD:ALL" > /etc/sudoers.d/user && \
    chmod 440 /etc/sudoers.d/user

USER ${USERNAME}
CMD [ "/bin/bash", "-l" ]
