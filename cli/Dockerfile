FROM python:3.8
RUN useradd --create-home --shell /bin/bash intertolls
WORKDIR /home/intertolls
COPY requirements.txt ./

RUN apt-get update && apt-get upgrade -y && apt-get autoremove && apt-get autoclean
RUN apt-get install -y \
    libffi-dev \
    libssl-dev \
    libfreetype6-dev \
    default-libmysqlclient-dev \
    zlib1g-dev \
    net-tools \
    vim \
    mariadb-server
RUN pip3 install --no-cache-dir -r requirements.txt
COPY .bashrc ./
COPY .bash.sh ./
COPY . ./cli
USER intertolls
CMD ["bash"]
