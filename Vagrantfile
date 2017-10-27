VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "centos/7"
  config.vm.network :private_network, ip: "10.10.10.40"
  config.vm.synced_folder ".", "/source/"
  Encoding.default_external = 'UTF-8'

  config.vm.provider :virtualbox do |vb|
    # vb.gui = true
    vb.customize ["modifyvm", :id, "--memory", "1024"]
  end

  config.vm.provision :shell, path: "provision.sh"

end
