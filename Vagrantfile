# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = "2"

ENV['PYTHONIOENCODING'] = "utf-8"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "ubuntu/trusty64"
  config.vm.hostname = "light.erniuniu.com.local"
  config.vm.network "private_network", ip: "192.168.33.33"
  config.vm.synced_folder "./", "/var/www/light.erniuniu.com.local", type: "nfs"
  config.vm.provider "virtualbox" do |vb|
    vb.cpus = 2
    vb.memory = 2048
  end
  config.vm.provision "ansible" do |ansible|
    ansible.playbook = "provisioning/playbook.yml"
    ansible.extra_vars = { ansible_ssh_user: 'vagrant' }
    ansible.verbose = "vvvv"
  end
end
